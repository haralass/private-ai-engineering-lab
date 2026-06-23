<?php
// driver_active_drives.php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['role'] ?? '') !== 'driver') {
    header("Location: login.php");
    exit;
}

$driver_username = $_SESSION['username'];

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

// Optional status message from actions
$msg = $_GET['msg'] ?? '';

$sql = "
SELECT 
    d.id AS segment_id,
    d.drive_request_number,
    d.segment_order,
    d.status AS segment_status,
    d.pick_up_latitude,
    d.pick_up_longitude,
    d.destination_latitude,
    d.destination_longitude,
    r.status AS request_status,
    r.user_username
FROM DRIVES d
JOIN USER_DRIVE_REQUESTS r
    ON r.[number] = d.drive_request_number
WHERE 
    d.assigned_driver_username = ?
    AND r.status IN ('ReadyToStart', 'InProgress') -- requests in play
ORDER BY d.drive_request_number, d.segment_order ASC;
";

$stmt = sqlsrv_query($conn, $sql, [$driver_username]);
$segments = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $segments[] = $row;
}
sqlsrv_free_stmt($stmt);

// Helper: for each request, find the lowest segment_order not completed
$nextAllowed = []; // request_number => segment_order

foreach ($segments as $seg) {
    $req = $seg['drive_request_number'];
    if (!isset($nextAllowed[$req]) || $seg['segment_status'] !== 'Completed') {
        if (!isset($nextAllowed[$req])) {
            $nextAllowed[$req] = $seg['segment_order'];
        } else {
            // Already set; but if this one is lower and not completed, prefer it
            if ($seg['segment_order'] < $nextAllowed[$req] && $seg['segment_status'] !== 'Completed') {
                $nextAllowed[$req] = $seg['segment_order'];
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Active Drives</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-4xl mx-auto bg-white shadow rounded-xl p-6 border border-gray-200">
    <h1 class="text-2xl font-bold mb-4 text-indigo-700">Active Drives for <?= htmlspecialchars($driver_username) ?></h1>

    <a href="driver_dashboard.php" class="text-sm text-indigo-600 hover:underline">&larr; Back to Driver Dashboard</a>

    <?php if ($msg): ?>
        <div class="mt-4 p-3 rounded bg-blue-100 text-blue-800 border border-blue-300">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <div class="mt-6 mb-6 p-4 bg-gray-50 border rounded">
        <h2 class="font-semibold mb-2">Update Your Location</h2>
        <form method="POST" action="driver_update_location.php" class="flex flex-wrap gap-2 items-center">
            <label class="text-sm">
                Latitude:
                <input type="number" step="0.0001" name="latitude" required class="border rounded px-2 py-1">
            </label>
            <label class="text-sm">
                Longitude:
                <input type="number" step="0.0001" name="longitude" required class="border rounded px-2 py-1">
            </label>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Update Location
            </button>
        </form>
        <p class="mt-2 text-xs text-gray-500">
            Your location will be visible to the user of the current in-progress segment (if any).
        </p>
    </div>

    <?php if (empty($segments)): ?>
        <p class="mt-4 text-gray-600">You currently have no active assigned segments.</p>
    <?php else: ?>
        <?php
        $currentRequest = null;
        foreach ($segments as $seg):
            if ($currentRequest !== $seg['drive_request_number']):
                if ($currentRequest !== null) echo "</div>";
                $currentRequest = $seg['drive_request_number'];
        ?>
            <div class="mt-4 mb-4 p-4 border rounded-lg bg-gray-50">
                <h2 class="font-semibold text-lg mb-2">
                    Request #<?= htmlspecialchars($seg['drive_request_number']) ?> 
                    <span class="text-sm text-gray-500">(User: <?= htmlspecialchars($seg['user_username']) ?>)</span>
                </h2>
        <?php endif; ?>

            <?php
                $canStart   = false;
                $canComplete= false;
                $segStatus  = $seg['segment_status'];
                $req        = $seg['drive_request_number'];

                // Determine if this is the next allowed segment for this request
                $isNextSegment = (isset($nextAllowed[$req]) && $nextAllowed[$req] == $seg['segment_order']);

                if ($segStatus === 'Assigned' && $isNextSegment) {
                    $canStart = true;
                }
                if ($segStatus === 'InProgress') {
                    $canComplete = true;
                }
            ?>

            <div class="mt-2 mb-2 p-3 bg-white rounded border flex justify-between items-center">
                <div>
                    <div class="font-semibold">
                        Segment order: <?= htmlspecialchars($seg['segment_order']) ?> 
                        <span class="ml-2 text-sm text-gray-500">Status: <?= htmlspecialchars($segStatus) ?></span>
                    </div>
                    <div class="text-xs text-gray-600">
                        From (<?= $seg['pick_up_latitude'] ?>, <?= $seg['pick_up_longitude'] ?>)
                        → To (<?= $seg['destination_latitude'] ?>, <?= $seg['destination_longitude'] ?>)
                    </div>
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="driver_segment_action.php">
                        <input type="hidden" name="segment_id" value="<?= $seg['segment_id'] ?>">
                        <input type="hidden" name="action" value="start">
                        <button 
                            type="submit"
                            class="px-3 py-1 rounded text-sm 
                                   <?= $canStart ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-300 text-gray-500 cursor-not-allowed' ?>"
                            <?= $canStart ? '' : 'disabled' ?>>
                            Start
                        </button>
                    </form>
                    <form method="POST" action="driver_segment_action.php">
                        <input type="hidden" name="segment_id" value="<?= $seg['segment_id'] ?>">
                        <input type="hidden" name="action" value="complete">
                        <button 
                            type="submit"
                            class="px-3 py-1 rounded text-sm 
                                   <?= $canComplete ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-300 text-gray-500 cursor-not-allowed' ?>"
                            <?= $canComplete ? '' : 'disabled' ?>>
                            Complete
                        </button>
                    </form>
                </div>
            </div>

        <?php endforeach; ?>
        </div> <!-- close last request box -->
    <?php endif; ?>

</div>
</body>
</html>
