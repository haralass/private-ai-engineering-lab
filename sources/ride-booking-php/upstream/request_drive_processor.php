<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([
        "status" => "error",
        "message" => "User not logged in."
    ]);
    exit;
}

$user_username = $_SESSION['username'];

$serverName = "mssql.cs.ucy.ac.cy";
$connectionOptions = [
    "Database" => "ihadji07",
    "Uid" => "ihadji07",
    "PWD" => "h6uXaQ4c"
];

function safe_query($conn, $sql, $params = [])
{
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        $err = print_r(sqlsrv_errors(), true);
        throw new Exception("SQL Error in query: {$sql} | {$err}");
    }
    return $stmt;
}

try {
    // Input validation
    $pick_up_lat = $_POST["pick_up_latitude"] ?? null;
    $pick_up_lon = $_POST["pick_up_longitude"] ?? null;
    $dest_lat    = $_POST["destination_latitude"] ?? null;
    $dest_lon    = $_POST["destination_longitude"] ?? null;
    $service     = $_POST["service_type"] ?? null;

    if (!$pick_up_lat || !$pick_up_lon || !$dest_lat || !$dest_lon || !$service) {
        throw new Exception("Missing required data.");
    }

    // Connect
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if ($conn === false) throw new Exception("Connection failed.");

    sqlsrv_begin_transaction($conn);

    // 1) INSERT USER REQUEST
    $insertSQL = "
        INSERT INTO USER_DRIVE_REQUESTS
        (user_username, pick_up_latitude, pick_up_longitude, destination_latitude, destination_longitude, service_type, estimated_cost)
        OUTPUT INSERTED.number
        VALUES (?, ?, ?, ?, ?, ?, 0);
    ";

    $stmt = safe_query($conn, $insertSQL, [
        $user_username,
        $pick_up_lat,
        $pick_up_lon,
        $dest_lat,
        $dest_lon,
        $service
    ]);

    sqlsrv_fetch($stmt);
    $request_number = sqlsrv_get_field($stmt, 0);

    // 2) CALCULATE SEGMENTS
    safe_query(
        $conn,
        "EXEC dbo.CalculateGreedyRoute ?, ?, ?, ?, ?",
        [$pick_up_lat, $pick_up_lon, $dest_lat, $dest_lon, $request_number]
    );

    // 3) GENERATE ROUTE OPTIONS
    safe_query(
        $conn,
        "EXEC dbo.GenerateRouteOptions ?, ?",
        [$request_number, 3]   // TOP 3 drivers per segment
    );

    sqlsrv_commit($conn);

    echo json_encode([
        "status" => "success",
        "request_number" => $request_number
    ]);
    exit;

} catch (Exception $e) {

    if (isset($conn)) sqlsrv_rollback($conn);

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
    exit;

} finally {
    if (isset($stmt)) sqlsrv_free_stmt($stmt);
    if (isset($conn)) sqlsrv_close($conn);
}
