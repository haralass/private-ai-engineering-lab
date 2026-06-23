<?php
session_start();

/* ---------------------------------------------------------
   Helper: HTML escape
--------------------------------------------------------- */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/* ---------------------------------------------------------
   1. Authentication
--------------------------------------------------------- */
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['username'])) {
    die("<h1>Session error: Missing username.</h1>");
}

$logged_user = $_SESSION['username'];

/* ---------------------------------------------------------
   2. Database connection
--------------------------------------------------------- */
$serverName = "mssql.cs.ucy.ac.cy";
$connectionOptions = [
    "Database"     => "ihadji07",
    "Uid"          => "ihadji07",
    "PWD"          => "h6uXaQ4c",
    "CharacterSet" => "UTF-8",
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die("<h1>Database connection failed.</h1>");
}

/* ---------------------------------------------------------
   3. Validate request parameter
--------------------------------------------------------- */
if (!isset($_GET['request'])) {
    die("<h1>Missing request parameter.</h1>");
}

$request = intval($_GET['request']);
if ($request <= 0) {
    die("<h1>Invalid request ID.</h1>");
}

/* ---------------------------------------------------------
   4. Fetch request details & verify ownership
--------------------------------------------------------- */
$sql_req = "
    SELECT number, status, user_username
    FROM USER_DRIVE_REQUESTS
    WHERE number = ?
";
$stmt_req = sqlsrv_query($conn, $sql_req, [$request]);

if ($stmt_req === false) {
    echo "<pre>"; print_r(sqlsrv_errors()); echo "</pre>";
    die("<h1>SQL error fetching request.</h1>");
}

$request_row = sqlsrv_fetch_array($stmt_req, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt_req);

if (!$request_row) {
    die("<h1>Request not found.</h1>");
}

if ($request_row['user_username'] !== $logged_user) {
    die("<h1>You are not authorized to view this request.</h1>");
}

/* ---------------------------------------------------------
   5. Fetch all route options (all paths × combinations)
--------------------------------------------------------- */
$sql_opt = "
    SELECT id, drive_request_number, path_id, total_distance_km, ranking_score
    FROM ROUTE_OPTIONS
    WHERE drive_request_number = ?
    ORDER BY path_id ASC, ranking_score ASC
";

$stmt_opt = sqlsrv_query($conn, $sql_opt, [$request]);

if ($stmt_opt === false) {
    echo "<pre>"; print_r(sqlsrv_errors()); echo "</pre>";
    die("<h1>SQL error fetching route options.</h1>");
}

$options = [];
while ($row = sqlsrv_fetch_array($stmt_opt, SQLSRV_FETCH_ASSOC)) {
    $options[] = $row;
}
sqlsrv_free_stmt($stmt_opt);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Route Option</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Leaflet maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        .map-container {
            height: 300px;
            margin-top: 15px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body class="bg-gray-100 p-6">

<h1 class="text-3xl font-bold mb-6 text-indigo-800">
    Select Route Option for Request #<?= h($request) ?>
</h1>

<?php if (empty($options)): ?>
    <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 p-4 rounded-lg">
        No route options are available for this request yet.<br>
        Please try again later.
    </div>

<?php else: ?>

    <?php foreach ($options as $opt): ?>
        <?php
        // For each option, fetch its segments with coordinates + vehicle + driver info
        $sql_seg = "
            SELECT 
                s.id AS segment_option_id,
                s.segment_distance_km,
                s.driver_username,
                s.vehicle_reg_number,

                d.segment_order,
                d.pick_up_latitude,
                d.pick_up_longitude,
                d.destination_latitude,
                d.destination_longitude,

                v.category,
                v.seat_num,
                v.max_cargo_weight,
                v.max_cargo_volume,

                drv.latitude AS driver_lat,
                drv.longitude AS driver_lon
            FROM ROUTE_OPTION_SEGMENTS s
            JOIN DRIVES d
              ON d.id = s.drive_segment_id
            JOIN VEHICLE v
              ON v.reg_number = s.vehicle_reg_number
            JOIN DRIVERS drv
              ON drv.username = s.driver_username
            WHERE s.route_option_id = ?
            ORDER BY d.segment_order ASC
        ";

        $stmt_seg = sqlsrv_query($conn, $sql_seg, [$opt['id']]);

        if ($stmt_seg === false) {
            echo "<pre>"; print_r(sqlsrv_errors()); echo "</pre>";
            die("<h1>SQL error fetching segments.</h1>");
        }

        $segments = [];
        while ($seg = sqlsrv_fetch_array($stmt_seg, SQLSRV_FETCH_ASSOC)) {
            $segments[] = $seg;
        }
        sqlsrv_free_stmt($stmt_seg);
        ?>

        <div class="bg-white shadow p-5 mb-6 rounded-lg border border-gray-200">

            <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                Route #<?= h($opt['path_id']) ?> &mdash; Option #<?= h($opt['id']) ?>
            </h2>

            <p class="text-gray-700 mb-3">
                <strong>Total Distance:</strong> <?= h($opt['total_distance_km']) ?> km<br>
                <strong>Ranking Score:</strong> <?= h($opt['ranking_score']) ?>
            </p>

            <!-- Map -->
            <div id="map_<?= h($opt['id']) ?>" class="map-container"></div>

            <h3 class="font-bold text-gray-900 mt-4 mb-2">Segments & Vehicles:</h3>

            <div class="space-y-3">
                <?php foreach ($segments as $index => $seg): ?>
                    <div class="p-3 bg-gray-50 rounded border">
                        <div><strong>Segment:</strong> <?= $index + 1 ?> (order <?= h($seg['segment_order']) ?>)</div>
                        <div><strong>Driver:</strong> <?= h($seg['driver_username']) ?></div>
                        <div><strong>Vehicle:</strong> <?= h($seg['vehicle_reg_number']) ?> (<?= h($seg['category']) ?>)</div>
                        <div><strong>Seats:</strong> <?= h($seg['seat_num']) ?></div>
                        <div><strong>Cargo capacity:</strong> 
                            <?= h($seg['max_cargo_weight']) ?> kg,
                            <?= h($seg['max_cargo_volume']) ?> m³
                        </div>
                        <div><strong>Segment distance:</strong> <?= h($seg['segment_distance_km']) ?> km</div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Expose segments to JS for maps -->
            <script>
                window.routeSegments_<?= (int)$opt['id'] ?> = <?= json_encode($segments) ?>;
            </script>

            <form method="POST" action="confirm_route_option.php" class="mt-5">
                <input type="hidden" name="request_number" value="<?= h($request) ?>">
                <input type="hidden" name="option_id" value="<?= h($opt['id']) ?>">

                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-lg shadow">
                    Choose this option
                </button>
            </form>
        </div>

    <?php endforeach; ?>

    <!-- Leaflet map initialization -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php foreach ($options as $opt): ?>
            (function() {
                const optId = <?= (int)$opt['id'] ?>;
                const segs = window.routeSegments_<?= (int)$opt['id'] ?> || [];

                const mapElementId = "map_" + optId;
                const mapElement = document.getElementById(mapElementId);
                if (!mapElement) return;

                const map = L.map(mapElementId);
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                const bounds = [];

                segs.forEach(function(seg) {
                    const p1 = [
                        parseFloat(seg.pick_up_latitude),
                        parseFloat(seg.pick_up_longitude)
                    ];
                    const p2 = [
                        parseFloat(seg.destination_latitude),
                        parseFloat(seg.destination_longitude)
                    ];

                    if (!isNaN(p1[0]) && !isNaN(p1[1]) && !isNaN(p2[0]) && !isNaN(p2[1])) {
                        bounds.push(p1, p2);

                        L.polyline([p1, p2]).addTo(map);
                        L.circleMarker(p1, {radius: 4}).addTo(map);
                        L.circleMarker(p2, {radius: 4}).addTo(map);
                    }
                });

                if (bounds.length > 0) {
                    map.fitBounds(bounds, {padding: [20, 20]});
                } else {
                    // default map center if no segment coords
                    map.setView([35.0, 33.0], 10);
                }
            })();
            <?php endforeach; ?>
        });
    </script>

<?php endif; ?>

</body>
</html>

<?php
sqlsrv_close($conn);
?>

