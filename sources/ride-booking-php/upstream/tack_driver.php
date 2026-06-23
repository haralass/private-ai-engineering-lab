<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$username  = $_SESSION['username'];
$user_role = $_SESSION['role'] ?? 'simple user';

if ($user_role !== 'simple user') {
    header("Location: dashboard.php");
    exit;
}

if (!isset($_GET['request'])) {
    die("Missing request number.");
}

$request_number = (int)$_GET['request'];

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

$driverLat = null;
$driverLon = null;
$driverName = null;
$infoMessage = "";

try {
    // 1. Check that this request belongs to the logged-in user
    $sqlReq = "
        SELECT user_username, status
        FROM USER_DRIVE_REQUESTS
        WHERE number = ?
    ";
    $stmtReq = safe_query($conn, $sqlReq, [$request_number]);
    $req = sqlsrv_fetch_array($stmtReq, SQLSRV_FETCH_ASSOC);

    if (!$req) {
        throw new Exception("Request not found.");
    }
    if ($req['user_username'] !== $username) {
        throw new Exception("You are not allowed to view this request.");
    }

    // 2. Find current active (InProgress) segment and its driver
    $sqlSeg = "
        SELECT TOP 1
            D.assigned_driver_username,
            DR.latitude,
            DR.longitude
        FROM DRIVES D
        INNER JOIN DRIVERS DR
            ON DR.username = D.assigned_driver_username
        WHERE 
            D.drive_request_number = ?
            AND D.status = 'InProgress'
    ";
    $stmtSeg = safe_query($conn, $sqlSeg, [$request_number]);
    $seg = sqlsrv_fetch_array($stmtSeg, SQLSRV_FETCH_ASSOC);

    if ($seg && $seg['latitude'] !== null && $seg['longitude'] !== null) {
        $driverLat  = (float)$seg['latitude'];
        $driverLon  = (float)$seg['longitude'];
        $driverName = $seg['assigned_driver_username'];
    } else {
        $infoMessage = "There is currently no active segment in progress for this request, or the driver has not shared their location yet.";
    }

} catch (Exception $e) {
    $infoMessage = $e->getMessage();
} finally {
    if (isset($stmtReq)) sqlsrv_free_stmt($stmtReq);
    if (isset($stmtSeg)) sqlsrv_free_stmt($stmtSeg);
    if ($conn) sqlsrv_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Your Driver</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        #map { height: 420px; border-radius: 12px; }
    </style>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-xl">
    <h1 class="text-2xl font-bold mb-4 text-indigo-700">
        Track Your Driver – Request #<?php echo htmlspecialchars($request_number); ?>
    </h1>

    <?php if ($driverLat !== null && $driverLon !== null): ?>
        <p class="mb-3 text-gray-700">
            Driver: <span class="font-semibold text-indigo-700">
                <?php echo htmlspecialchars($driverName); ?>
            </span>
        </p>
        <div id="map" class="mb-4"></div>

        <script>
            const driverLat = <?php echo json_encode($driverLat); ?>;
            const driverLon = <?php echo json_encode($driverLon); ?>;

            const map = L.map('map').setView([driverLat, driverLon], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            const marker = L.marker([driverLat, driverLon]).addTo(map)
                .bindPopup("Your driver is here.")
                .openPopup();
        </script>
    <?php endif; ?>

    <?php if ($infoMessage): ?>
        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800">
            <?php echo htmlspecialchars($infoMessage); ?>
        </div>
    <?php endif; ?>

    <div class="mt-6">
        <a href="dashboard.php" class="text-indigo-600 hover:text-indigo-800 text-sm">
            &larr; Back to Dashboard
        </a>
    </div>
</div>
</body>
</html>
