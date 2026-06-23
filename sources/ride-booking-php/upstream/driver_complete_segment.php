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

    // 1. Get segment info
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

    if ($seg['status'] !== 'InProgress') {
        throw new Exception("Segment must be 'InProgress' to complete. Current: " . $seg['status']);
    }

    $request_number = (int)$seg['drive_request_number'];

    // 2. Mark segment as Completed
    $sqlUpdSeg = "
        UPDATE DRIVES
        SET status = 'Completed'
        WHERE id = ?
    ";
    safe_query($conn, $sqlUpdSeg, [$segment_id]);

    // 3. If driver has no other InProgress segments → set Available
    $sqlActive = "
        SELECT COUNT(*) AS active_cnt
        FROM DRIVES
        WHERE assigned_driver_username = ?
          AND status = 'InProgress'
    ";
    $stmtActive = safe_query($conn, $sqlActive, [$driver_username]);
    $activeRow = sqlsrv_fetch_array($stmtActive, SQLSRV_FETCH_ASSOC);

    if ($activeRow['active_cnt'] == 0) {
        $sqlDrv = "
            UPDATE DRIVERS
            SET status = 'Available'
            WHERE username = ?
        ";
        safe_query($conn, $sqlDrv, [$driver_username]);
    }

    // 4. Check if ALL segments of this request are now completed
    $sqlRemain = "
        SELECT COUNT(*) AS remaining
        FROM DRIVES
        WHERE drive_request_number = ?
          AND status <> 'Completed'
    ";
    $stmtRemain = safe_query($conn, $sqlRemain, [$request_number]);
    $remRow = sqlsrv_fetch_array($stmtRemain, SQLSRV_FETCH_ASSOC);

    if ($remRow['remaining'] == 0) {
        // Request fully completed
        $sqlReq = "
            UPDATE USER_DRIVE_REQUESTS
            SET status = 'Completed'
            WHERE number = ?
        ";
        safe_query($conn, $sqlReq, [$request_number]);
    }

    sqlsrv_commit($conn);

    header("Location: driver_dashboard.php?msg=segment_completed");
    exit;

} catch (Exception $e) {
    sqlsrv_rollback($conn);
    echo "<h2>Cannot complete segment</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
} finally {
    if (isset($stmtSeg)) sqlsrv_free_stmt($stmtSeg);
    if ($conn) sqlsrv_close($conn);
}
