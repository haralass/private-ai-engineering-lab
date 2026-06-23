<?php
session_start();

/* ----------------------------------
   AUTH CHECK
---------------------------------- */
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$user_username = $_SESSION['username'];

/* ----------------------------------
   REQUEST PARAM
---------------------------------- */
$request_number = isset($_GET['request']) ? intval($_GET['request']) : 0;

if ($request_number <= 0) {
    die("Invalid request ID.");
}

/* ----------------------------------
   DB CONNECTION
---------------------------------- */
$serverName = "mssql.cs.ucy.ac.cy";
$connectionOptions = [
    "Database"     => "ihadji07",
    "Uid"          => "ihadji07",
    "PWD"          => "h6uXaQ4c",
    "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) die("Database connection failed.");

/* ----------------------------------
   VERIFY REQUEST BELONGS TO USER
---------------------------------- */
$sql = "
SELECT number, status
FROM USER_DRIVE_REQUESTS
WHERE number = ? AND user_username = ?
";
$stmt = sqlsrv_query($conn, $sql, [$request_number, $user_username]);
$request = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt);

if (!$request) die("You are not authorized to view this request.");

/* ----------------------------------
   GET CURRENT ACTIVE DRIVER + SEGMENT
---------------------------------- */
$sql = "
SELECT TOP 1
    d.id AS segment_id,
    d.assigned_driver_username,
    d.segment_order,
    d.pick_up_latitude,
    d.pick_up_longitude,
    d.destination_latitude,
    d.destination_longitude,
    dr.latitude AS driver_lat,
    dr.longitude AS driver_lon
FROM DRIVES d
JOIN DRIVERS dr ON dr.username = d.assigned_driver_username
WHERE d.drive_request_number = ?
  AND d.status = 'InProgress'
ORDER BY d.segment_order ASC
";
$stmt = sqlsrv_query($conn, $sql, [$request_number]);
$active = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt);

sqlsrv_close($conn);

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Track Your Driver</title>
<script src="https://cdn.tailwindcss.com"></script>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

</head>
<body class="bg-gray-100 p-6">

<div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6 border border-gray-200">
    <h1 class="text-2xl font-bold text-indigo-700 mb-4">
        Tracking Request #<?= h($request_number) ?>
    </h1>

    <p class="text-sm text-gray-600 mb-4">
        Driver location updates automatically every 5 seconds.
    </p>

    <?php if (!$active): ?>

        <div class="p-4 bg-yellow-50 border border-yellow-300 rounded text-yellow-800 mb-4">
            <?php if ($request['status'] === 'Completed'): ?>
                Your ride has been <strong>completed</strong>.
            <?php else: ?>
                The driver is not yet actively driving a segment for this request.
            <?php endif; ?>
        </div>

    <?php else: ?>

        <!-- Current driver info -->
        <div class="p-4 bg-green-50 border border-green-300 rounded text-green-800 mb-4">
            <p><strong>Current driver:</strong> <?= h($active['assigned_driver_username']) ?></p>
            <p><strong>Current Segment:</strong> <?= h($active['segment_order']) ?></p>
        </div>

        <!-- The map -->
        <div id="map" class="w-full h-80 rounded border border-gray-300"></div>

        <script>
        let map = L.map('map');
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
        }).addTo(map);

        // Segment endpoints from PHP
        let p1 = [
            <?= $active['pick_up_latitude'] ?>,
            <?= $active['pick_up_longitude'] ?>
        ];
        let p2 = [
            <?= $active['destination_latitude'] ?>,
            <?= $active['destination_longitude'] ?>
        ];

        // Draw segment polyline
        let segLine = L.polyline([p1, p2], {color: 'blue'}).addTo(map);
        map.fitBounds(segLine.getBounds(), {padding: [20, 20]});

        // Driver marker (initial)
        let driverMarker = null;

        function updateDriverMarker(lat, lon) {
            if (!driverMarker) {
                driverMarker = L.marker([lat, lon]).addTo(map);
            } else {
                driverMarker.setLatLng([lat, lon]);
            }
        }

        // Initial driver location
        updateDriverMarker(
            <?= $active['driver_lat'] ?>,
            <?= $active['driver_lon'] ?>
        );

        // ---------------------------------------------
        // AUTO REFRESH DRIVER LOCATION (every 5 seconds)
        // ---------------------------------------------
        function fetchDriverLocation() {
            fetch("user_track_request_poll.php?request=<?= $request_number ?>")
                .then(r => r.json())
                .then(data => {
                    if (data.status === "active") {
                        updateDriverMarker(data.lat, data.lon);
                    }
                });
        }

        setInterval(fetchDriverLocation, 5000);
        </script>

    <?php endif; ?>

    <a href="dashboard.php"
       class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
       Back to Dashboard
    </a>

</div>
</body>
</html>
