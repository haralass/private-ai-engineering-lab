<?php
// driver_segment_action.php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['role'] ?? '') !== 'driver') {
    header("Location: login.php");
    exit;
}

$driver_username = $_SESSION['username'];

$action    = $_POST['action'] ?? '';
$segmentId = isset($_POST['segment_id']) ? (int)$_POST['segment_id'] : 0;

if (!$segmentId || !in_array($action, ['start','complete'], true)) {
    header("Location: driver_active_drives.php?msg=" . urlencode("Invalid operation."));
    exit;
}

$serverName = "mssql.cs.ucy.ac.cy";
$connectionOptions = [
    "Database" => "ihadji07",
    "Uid"      => "ihadji07",
    "PWD"      => "h6uXaQ4c"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die("Database connection failed.");
}

sqlsrv_begin_transaction($conn);

// 1. Load segment details
$sql = "
SELECT 
    d.id,
    d.drive_request_number,
    d.segment_order,
    d.status,
    d.assigned_driver_username,
    ur.status AS request_status
FROM DRIVES d
JOIN USER_DRIVE_REQUESTS ur
    ON ur.[number] = d.drive_request_number
WHERE d.id = ?
";
$stmt = sqlsrv_query($conn, $sql, [$segmentId]);
$seg  = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt);

if (!$seg || $seg['assigned_driver_username'] !== $driver_username) {
    sqlsrv_rollback($conn);
    header("Location: driver_active_drives.php?msg=" . urlencode("You are not assigned to this segment."));
    exit;
}

$reqNumber    = $seg['drive_request_number'];
$segOrder     = $seg['segment_order'];
$segStatus    = $seg['status'];
$requestStatus= $seg['request_status'];

// Helper: does driver already have any InProgress segment?
$sql = "
SELECT TOP 1 1
FROM DRIVES
WHERE assigned_driver_username = ?
  AND status = 'InProgress'
";
$stmt = sqlsrv_query($conn, $sql, [$driver_username]);
$driverHasActive = (sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC) !== false);
sqlsrv_free_stmt($stmt);

if ($action === 'start') {

    if ($segStatus !== 'Assigned') {
        sqlsrv_rollback($conn);
        header("Location: driver_active_drives.php?msg=" . urlencode("Segment cannot be started in its current state."));
        exit;
    }

    if ($requestStatus !== 'ReadyToStart' && $requestStatus !== 'InProgress') {
        sqlsrv_rollback($conn);
        header("Location: driver_active_drives.php?msg=" . urlencode("Request is not ready to start yet."));
        exit;
    }

    if ($driverHasActive) {
        sqlsrv_rollback($conn);
        header("Location: driver_active_drives.php?msg=" . urlencode("You already have an active segment in progress."));
        exit;
    }

    // Check all previous segments for this request are completed
    $sql = "
    SELECT TOP 1 1
    FROM DRIVES
    WHERE drive_request_number = ?
      AND segment_order < ?
      AND status <> 'Completed'
    ";
    $stmt = sqlsrv_query($conn, $sql, [$reqNumber, $segOrder]);
    $hasUnfinishedBefore = (sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC) !== false);
    sqlsrv_free_stmt($stmt);

    if ($hasUnfinishedBefore) {
        sqlsrv_rollback($conn);
        header("Location: driver_active_drives.php?msg=" . urlencode("You cannot start this segment before previous segments are completed."));
        exit;
    }

    // All good: set this segment InProgress, request InProgress, driver On-Drive
    $sql = "UPDATE DRIVES SET status = 'InProgress' WHERE id = ?";
    sqlsrv_query($conn, $sql, [$segmentId]);

    $sql = "UPDATE USER_DRIVE_REQUESTS SET status = 'InProgress' WHERE [number] = ?";
    sqlsrv_query($conn, $sql, [$reqNumber]);

    $sql = "UPDATE DRIVERS SET status = 'On-Drive' WHERE username = ?";
    sqlsrv_query($conn, $sql, [$driver_username]);

    sqlsrv_commit($conn);
    header("Location: driver_active_drives.php?msg=" . urlencode("Segment started."));
    exit;
}

if ($action === 'complete') {

    if ($segStatus !== 'InProgress') {
        sqlsrv_rollback($conn);
        header("Location: driver_active_drives.php?msg=" . urlencode("Only an in-progress segment can be completed."));
        exit;
    }

    // Mark this segment completed
    $sql = "UPDATE DRIVES SET status = 'Completed' WHERE id = ?";
    sqlsrv_query($conn, $sql, [$segmentId]);

    // Free the driver
    $sql = "UPDATE DRIVERS SET status = 'Available' WHERE username = ?";
    sqlsrv_query($conn, $sql, [$driver_username]);

    // Check if all segments of this request are now completed
    $sql = "
    SELECT TOP 1 1
    FROM DRIVES
    WHERE drive_request_number = ?
      AND status <> 'Completed'
    ";
    $stmt = sqlsrv_query($conn, $sql, [$reqNumber]);
    $hasRemaining = (sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC) !== false);
    sqlsrv_free_stmt($stmt);

    if (!$hasRemaining) {
        // All segments done
        $sql = "UPDATE USER_DRIVE_REQUESTS SET status = 'Completed' WHERE [number] = ?";
        sqlsrv_query($conn, $sql, [$reqNumber]);
    } else {
        // Still more segments to serve: request stays InProgress
        $sql = "UPDATE USER_DRIVE_REQUESTS SET status = 'InProgress' WHERE [number] = ?";
        sqlsrv_query($conn, $sql, [$reqNumber]);
    }

    sqlsrv_commit($conn);
    header("Location: driver_active_drives.php?msg=" . urlencode("Segment completed."));
    exit;
}

sqlsrv_rollback($conn);
header("Location: driver_active_drives.php?msg=" . urlencode("Unknown action."));
exit;
