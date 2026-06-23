<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Database connection
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

function safe_query($conn, $sql, $params = [])
{
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        $err = print_r(sqlsrv_errors(), true);
        throw new Exception("SQL error in: {$sql} | {$err}");
    }
    return $stmt;
}

try {
    if (!isset($_POST["request_number"]) || !isset($_POST["option_id"])) {
        throw new Exception("Missing request data.");
    }

    $request_number = intval($_POST["request_number"]);
    $option_id      = intval($_POST["option_id"]);

    sqlsrv_begin_transaction($conn);

    // ------------------------------------------------------------
    // 1. INSERT THE SELECTED OPTION
    // ------------------------------------------------------------
    $insertSelectSQL = "
        MERGE SELECTED_ROUTE_OPTION AS tgt
        USING (SELECT ? AS request_number, ? AS option_id) AS src
        ON tgt.request_number = src.request_number
        WHEN MATCHED THEN
            UPDATE SET option_id = src.option_id, chosen_at = GETDATE()
        WHEN NOT MATCHED THEN
            INSERT (request_number, option_id) VALUES (src.request_number, src.option_id);
    ";
    safe_query($conn, $insertSelectSQL, [$request_number, $option_id]);

    // ------------------------------------------------------------
    // 2. GET SEGMENTS BELONGING TO THIS OPTION
    // ------------------------------------------------------------
    $segSQL = "
        SELECT 
            ROS.drive_segment_id,
            ROS.driver_username,
            ROS.vehicle_reg_number
        FROM ROUTE_OPTION_SEGMENTS ROS
        WHERE ROS.route_option_id = ?
        ORDER BY ROS.drive_segment_id ASC;
    ";
    $segStmt = safe_query($conn, $segSQL, [$option_id]);

    $segments = [];
    while ($row = sqlsrv_fetch_array($segStmt, SQLSRV_FETCH_ASSOC)) {
        $segments[] = $row;
    }

    if (empty($segments)) {
        throw new Exception("No segments found for selected option.");
    }

    // ------------------------------------------------------------
    // 3. CREATE REQUEST_OFFERS (one for each driver in the option)
    // ------------------------------------------------------------
    $offerSQL = "
        INSERT INTO REQUEST_OFFERS
        (drive_request_number, driver_username, vehicle_reg_number, drive_segment_id, status)
        VALUES (?, ?, ?, ?, 'Pending');
    ";

    foreach ($segments as $seg) {
        safe_query($conn, $offerSQL, [
            $request_number,
            $seg["driver_username"],
            $seg["vehicle_reg_number"],
            $seg["drive_segment_id"]
        ]);
    }

    // ------------------------------------------------------------
    // 4. MARK THE DRIVE SEGMENTS AS "Offering"
    // ------------------------------------------------------------
    $updateDriveSQL = "
        UPDATE DRIVES
        SET status = 'Offering'
        WHERE drive_request_number = ?;
    ";
    safe_query($conn, $updateDriveSQL, [$request_number]);

    sqlsrv_commit($conn);

    // ------------------------------------------------------------
    // REDIRECT USER TO WAITING SCREEN
    // ------------------------------------------------------------
    header("Location: waiting_for_drivers.php?request={$request_number}");
    exit;

} catch (Exception $e) {

    if (isset($conn)) {
        sqlsrv_rollback($conn);
    }

    echo "<h2>Error</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    exit;

} finally {
    if (isset($segStmt)) sqlsrv_free_stmt($segStmt);
    if (isset($conn)) sqlsrv_close($conn);
}
