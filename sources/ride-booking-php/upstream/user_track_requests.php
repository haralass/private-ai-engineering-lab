<?php
// user_track_request.php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$user_username = $_SESSION['username'];

$request_number = isset($_GET['request']) ? (int)$_GET['request'] : 0;
if ($request_number <= 0) {
    die("Invalid request ID.");
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

// Verify that this request belongs to the logged-in user
$sql = "
SELECT [number], status
FROM USER_DRIVE_REQUESTS
WHERE [number] = ? AND user_username = ?
";
$stmt = sqlsrv_query($conn, $sql, [$request_number, $user_username]);
$request = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt);

if (!$request) {
    die("You are not authorized to view this request.");
}

// Find current active driver: any segment InProgress
$sql = "
SELECT TOP 1
    d.assigned_driver_username,
    dr.latitude,
    dr.longitude,
    d.segment_order
FROM DRIVES d
JOIN DRIVERS dr
    ON dr.username = d.assigned_driver_username
WHERE 
    d.drive_request_number = ?
    AND d.status = 'InProgress'
";
$stmt = sqlsrv_query($conn, $sql, [$request_number]);
$active = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Your Driver</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta http-equiv="refresh" content="10"> <!-- auto-refresh every 10s -->
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6 border border-gray-200">
    <h1 class="text-2xl font-bold text-indigo-700 mb-4">
        Tracking Request #<?= htmlspecialchars($request_number) ?>
    </h1>

    <p class="text-sm text-gray-600 mb-4">
        Page refreshes every 10 seconds. You will only see the location of the driver currently serving your request (if any).
    </p>

    <?php if (!$active): ?>
        <div class="p-4 bg-yellow-50 border border-yellow-300 rounded text-yellow-800">
            <?php if ($request['status'] === 'Completed'): ?>
                Your ride has been <strong>completed</strong>. No active driver.
            <?php elseif ($request['status'] === 'ReadyToStart' || $request['status'] === 'AwaitingDrivers'): ?>
                Your ride has not started yet. The driver is not active.
            <?php else: ?>
                No driver is currently in progress for this request.
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="p-4 bg-green-50 border border-green-300 rounded text-green-800">
            <p><strong>Current driver:</strong> <?= htmlspecialchars($active['assigned_driver_username']) ?></p>
            <p><strong>Segment order:</strong> <?= htmlspecialchars($active['segment_order']) ?></p>
            <p class="mt-2">
                <strong>Driver location:</strong><br>
                Latitude: <?= htmlspecialchars($active['latitude']) ?><br>
                Longitude: <?= htmlspecialchars($active['longitude']) ?>
            </p>
        </div>
    <?php endif; ?>

    <a href="dashboard.php" class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        Back to Dashboard
    </a>
</div>
</body>
</html>
