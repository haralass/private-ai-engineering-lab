<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(["status" => "error", "message" => "Not logged in."]);
    exit;
}
if (($_SESSION['role'] ?? '') !== 'driver') {
    echo json_encode(["status" => "error", "message" => "Only drivers can update location."]);
    exit;
}

$driver_username = $_SESSION['username'];

$lat = isset($_POST['latitude'])  ? floatval($_POST['latitude'])  : null;
$lon = isset($_POST['longitude']) ? floatval($_POST['longitude']) : null;

if ($lat === null || $lon === null) {
    echo json_encode(["status" => "error", "message" => "Missing latitude or longitude."]);
    exit;
}

$serverName = "mssql.cs.ucy.ac.cy";
$connectionOptions = [
    "Database" => "ihadji07",
    "Uid" => "ihadji07",
    "PWD" => "h6uXaQ4c"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    echo json_encode(["status" => "error", "message" => "DB connection failed."]);
    exit;
}

$sql = "UPDATE DRIVERS SET latitude = ?, longitude = ? WHERE username = ?";
$stmt = sqlsrv_query($conn, $sql, [$lat, $lon, $driver_username]);

if ($stmt === false) {
    echo json_encode(["status" => "error", "message" => "Failed to update location."]);
    sqlsrv_close($conn);
    exit;
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

echo json_encode(["status" => "success"]);
