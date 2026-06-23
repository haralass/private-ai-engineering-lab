<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
if (($_SESSION['role'] ?? '') !== 'driver') {
    header("Location: dashboard.php");
    exit;
}

$driver_username = $_SESSION['username'];

if (!isset($_POST['drive_segment_id'])) {
    die("Missing segment id.");
}

$segment_id = (int)$_POST['drive_segment_id'];

$serverName = "mssql.cs.ucy.ac.cy";
$connectionOptions = [
    "Database" => "ihadji07",
    "Uid" => "ihadji07",
    "PWD" => "h6uXaQ4c"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die("Database connection failed.");
}

function safe_query($conn, $sql, $params = []) {
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        $err = print_r(sqlsrv_errors(), true);
        throw new Exception("SQL error in: {$sql} | {$err}");
    }
    return $stmt;
}

try {
    sqlsrv_begin_transaction($conn);

    // 1. Get segment info and verify ownership + status
    $sqlSeg = "
        SELECT drive_request_number, segment_order, status, assigned_driver_username
        FROM DRIVES
        WHERE id = ?
    ";
    $stmtSeg = safe_query($conn, $sqlSeg, [$segment_id]);
    $seg = sqlsrv_fetch_array($stmtSeg, SQLSRV_FETCH_ASSOC);

    if (!$seg) {
        throw new Exception("Segment not found.");
    }

    if ($seg['assigned_driver_username'] !== $driver_username) {
        throw new Exception("This segment is not assigned to you.");
    }

    if ($seg['status'] !== 'Assigned') {
        throw new Exception("Segment must be in 'Assigned' status to start. Current: " . $seg['status']);
    }

    $request_number = (int)$seg['drive_request_number'];
    $seg_order      = (int)$seg['segment_order'];

    // 2. Ensure no other segment is currently InProgress for this driver
    $sqlActive = "
        SELECT COUNT(*) AS active_cnt
        FROM DRIVES
        WHERE assigned_driver_username = ?
          AND status = 'InProgress'
    ";
    $stmtActive = safe_query($conn, $sqlActive, [$driver_username]);
    $activeRow = sqlsrv_fetch_array($stmtActive, SQLSRV_FETCH_ASSOC);

    if ($activeRow['active_cnt'] > 0) {
        throw new Exception("You already have a segment in progress.");
    }

    // 3. Ensure all previous segments in this request are Completed
    $sqlPrev = "
        SELECT COUNT(*) AS not_done
        FROM DRIVES
        WHERE drive_request_number = ?
          AND segment_order < ?
          AND status <> 'Completed'
    ";
    $stmtPrev = safe_query($conn, $sqlPrev, [$request_number, $seg_order]);
    $prevRow = sqlsrv_fetch_array($stmtPrev, SQLSRV_FETCH_ASSOC);

    if ($prevRow['not_done'] > 0) {
        throw new Exception("Previous segments are not completed yet. You cannot start this one.");
    }

    // 4. Update segment status -> InProgress
    $sqlUpdSeg = "
        UPDATE DRIVES
        SET status = 'InProgress'
        WHERE id = ?
    ";
    safe_query($conn, $sqlUpdSeg, [$segment_id]);

    // 5. Optionally update driver status to 'On-Drive'
    $sqlUpdDrv = "
        UPDATE DRIVERS
        SET status = 'On-Drive'
        WHERE username = ?
    ";
    safe_query($conn, $sqlUpdDrv, [$driver_username]);

    sqlsrv_commit($conn);

    header("Location: driver_dashboard.php?msg=segment_started");
    exit;

} catch (Exception $e) {
    sqlsrv_rollback($conn);
    echo "<h2>Cannot start segment</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
} finally {
    if (isset($stmtSeg)) sqlsrv_free_stmt($stmtSeg);
    if ($conn) sqlsrv_close($conn);
}
