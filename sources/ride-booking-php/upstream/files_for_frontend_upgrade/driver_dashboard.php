<?php
session_start();

// -----------------------------------------------------
// AUTH & ROLE CHECK
// -----------------------------------------------------
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$username  = $_SESSION['username'];
$user_role = $_SESSION['role'] ?? 'simple user';

if ($user_role !== 'driver') {
    header("Location: dashboard.php");
    exit;
}

// -----------------------------------------------------
// DB CONNECTION
// -----------------------------------------------------
$serverName = "mssql.cs.ucy.ac.cy";
$connectionOptions = [
    "Database"     => "ihadji07",
    "Uid"          => "ihadji07",
    "PWD"          => "h6uXaQ4c",
    "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die("❌ Database connection failed.");
}

function safe_query($conn, $sql, $params = []) {
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        $err = print_r(sqlsrv_errors(), true);
        die("SQL ERROR: $err");
    }
    return $stmt;
}

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// -----------------------------------------------------------------------------
// 1) LOAD PENDING OFFERS FOR THIS DRIVER
// -----------------------------------------------------------------------------
$offers = [];
$sqlPending = "
    SELECT 
        RO.id                  AS offer_id,
        RO.drive_request_number,
        RO.drive_segment_id,
        RO.offer_time,
        RO.vehicle_reg_number,
        R.user_username        AS customer_username,
        R.service_type,
        R.estimated_cost,
        D.segment_order,
        D.pick_up_latitude     AS seg_pick_lat,
        D.pick_up_longitude    AS seg_pick_lon,
        D.destination_latitude AS seg_dest_lat,
        D.destination_longitude AS seg_dest_lon,
        R.pick_up_latitude     AS req_pick_lat,
        R.pick_up_longitude    AS req_pick_lon,
        R.destination_latitude AS req_dest_lat,
        R.destination_longitude AS req_dest_lon
    FROM REQUEST_OFFERS RO
    INNER JOIN DRIVES D
        ON D.id = RO.drive_segment_id
    INNER JOIN USER_DRIVE_REQUESTS R
        ON R.number = RO.drive_request_number
    WHERE 
        RO.driver_username = ?
        AND RO.status = 'Pending'
    ORDER BY RO.offer_time DESC;
";

$stmt = safe_query($conn, $sqlPending, [$username]);

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $row['seg_pick_display'] = "Lat " . number_format($row['seg_pick_lat'], 4) .
                               ", Lon " . number_format($row['seg_pick_lon'], 4);
    $row['seg_dest_display'] = "Lat " . number_format($row['seg_dest_lat'], 4) .
                               ", Lon " . number_format($row['seg_dest_lon'], 4);
    $offers[] = $row;
}

// -----------------------------------------------------------------------------
// 2) LOAD ACTIVE / ASSIGNED SEGMENTS FOR THIS DRIVER
// -----------------------------------------------------------------------------
$active_segments = [];
$sqlActive = "
    SELECT 
        D.id AS segment_id,
        D.drive_request_number,
        D.segment_order,
        D.status,
        D.pick_up_latitude,
        D.pick_up_longitude,
        D.destination_latitude,
        D.destination_longitude
    FROM DRIVES D
    WHERE D.assigned_driver_username = ?
      AND D.status IN ('Assigned','InProgress')
    ORDER BY D.drive_request_number ASC, D.segment_order ASC;
";

$stmt2 = safe_query($conn, $sqlActive, [$username]);

while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $active_segments[] = $row;
}

sqlsrv_free_stmt($stmt);
sqlsrv_free_stmt($stmt2);
sqlsrv_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
<div class="max-w-5xl mx-auto bg-white p-6 rounded-xl shadow-xl">

    <h1 class="text-3xl font-bold text-indigo-700 mb-4">
        Welcome, <?= h($username) ?> (Driver)
    </h1>

    <!-- ===========================
          ACTIVE / ASSIGNED SEGMENTS
    ============================ -->
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">
        Your Active Segments
    </h2>

    <?php if (empty($active_segments)): ?>
        <div class="p-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg mb-6">
            No active or assigned segments at the moment.
        </div>
    <?php else: ?>
        <?php foreach ($active_segments as $seg): ?>
            <div class="p-4 mb-4 bg-gray-50 border border-gray-200 rounded-lg">
                <p class="text-sm text-gray-600">
                    Request #<?= h($seg['drive_request_number']) ?>
                </p>
                <p class="text-sm mb-2">
                    Segment <?= h($seg['segment_order']) ?> —
                    Status:
                    <span class="font-semibold text-indigo-700">
                        <?= h($seg['status']) ?>
                    </span>
                </p>

                <p class="text-xs text-gray-500">
                    From: Lat <?= number_format($seg['pick_up_latitude'], 4) ?>,
                    Lon <?= number_format($seg['pick_up_longitude'], 4) ?><br>
                    To: Lat <?= number_format($seg['destination_latitude'], 4) ?>,
                    Lon <?= number_format($seg['destination_longitude'], 4) ?>
                </p>

                <div class="flex flex-wrap gap-3 mt-3">

                    <!-- Start Button -->
                    <?php if ($seg['status'] === 'Assigned'): ?>
                        <form method="POST" action="driver_start_segment.php">
                            <input type="hidden" name="drive_segment_id" value="<?= h($seg['segment_id']) ?>">
                            <button class="px-4 py-2 bg-green-600 text-white rounded shadow hover:bg-green-700 text-sm">
                                Start Segment
                            </button>
                        </form>
                    <?php endif; ?>

                    <!-- Finish Button -->
                    <?php if ($seg['status'] === 'InProgress'): ?>
                        <form method="POST" action="driver_complete_segment.php">
                            <input type="hidden" name="drive_segment_id" value="<?= h($seg['segment_id']) ?>">
                            <button class="px-4 py-2 bg-emerald-600 text-white rounded shadow hover:bg-emerald-700 text-sm">
                                Complete Segment
                            </button>
                        </form>
                    <?php endif; ?>

                    <!-- Update Location -->
                    <button onclick="updateLocation()"
                        class="px-4 py-2 bg-indigo-500 text-white rounded shadow hover:bg-indigo-600 text-sm">
                        Update My Location
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- ===========================
           PENDING OFFERS
    ============================ -->
    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">
        Pending Offers
    </h2>

    <?php if (empty($offers)): ?>
        <p class="p-4 bg-gray-100 border border-gray-200 text-gray-600 rounded-lg">
            No pending offers right now.
        </p>
    <?php else: ?>
        <div class="grid md:grid-cols-2 gap-4">
            <?php foreach ($offers as $offer): ?>
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow">
                    <h3 class="font-semibold text-indigo-700">
                        Request #<?= h($offer['drive_request_number']) ?>
                    </h3>

                    <p class="text-xs text-gray-500">
                        Customer: <?= h($offer['customer_username']) ?>
                    </p>

                    <p class="text-sm text-gray-600 mt-2">
                        Segment <?= h($offer['segment_order']) ?>
                    </p>

                    <p class="text-sm text-gray-600 mt-1">
                        Pickup: <?= h($offer['seg_pick_display']) ?>
                    </p>
                    <p class="text-sm text-gray-600">
                        Destination: <?= h($offer['seg_dest_display']) ?>
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Service: <?= h($offer['service_type']) ?>,
                        Estimate: <?= h($offer['estimated_cost']) ?> €
                    </p>

                    <form method="POST" action="driver_response_processor.php"
                          class="flex space-x-3 mt-4">
                        <input type="hidden" name="offer_id" value="<?= h($offer['offer_id']) ?>">
                        <input type="hidden" name="drive_segment_id" value="<?= h($offer['drive_segment_id']) ?>">
                        <input type="hidden" name="request_number" value="<?= h($offer['drive_request_number']) ?>">
                        <input type="hidden" name="vehicle_reg_number" value="<?= h($offer['vehicle_reg_number']) ?>">

                        <button name="response" value="Accepted"
                                class="flex-1 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                            Accept
                        </button>
                        <button name="response" value="Rejected"
                                class="flex-1 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                            Reject
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="mt-8 border-t pt-4 flex justify-between items-center">
        <a href="dashboard.php"
           class="text-sm text-gray-600 hover:text-gray-800">
            ⬅ Back to main dashboard
        </a>

        <a href="logout.php"
           class="text-sm text-red-500 hover:text-red-700 font-medium">
            Log Out
        </a>
    </div>

</div>

<script>
// ------------------------------------------------------------------
// UPDATE DRIVER LOCATION (AJAX + browser GPS)
// ------------------------------------------------------------------
function updateLocation() {
    if (!navigator.geolocation) {
        alert("Your browser does not support GPS.");
        return;
    }

    navigator.geolocation.getCurrentPosition(function(pos) {
        const lat = pos.coords.latitude;
        const lon = pos.coords.longitude;

        fetch("driver_update_location.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "latitude=" + encodeURIComponent(lat) +
                  "&longitude=" + encodeURIComponent(lon)
        })
        .then(r => r.json())
        .then(response => {
            if (response.status === "success") {
                alert("Location updated successfully.");
            } else {
                alert("Failed to update location: " + (response.message || "Unknown error"));
            }
        })
        .catch(() => {
            alert("Error while sending location to server.");
        });

    }, function() {
        alert("Unable to retrieve your location.");
    });
}
</script>

</body>
</html>
