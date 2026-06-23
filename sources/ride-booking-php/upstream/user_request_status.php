<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['username'];

// --- SQL SERVER CONNECTION ---
$serverName = "mssql.cs.ucy.ac.cy";
$connectionOptions = [
    "Database" => "ihadji07",
    "Uid"      => "ihadji07",
    "PWD"      => "h6uXaQ4c"
];
// -----------------------------

$request_number = isset($_GET['request']) ? (int)$_GET['request'] : 0;

if ($request_number <= 0) {
    die("Invalid request.");
}

$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die("Database connection failed.");
}

// Fetch request details
$sql = "
SELECT 
    [number], 
    user_username,
    status,
    pick_up_latitude,
    pick_up_longitude,
    destination_latitude,
    destination_longitude,
    service_type,
    chosen_route.option_id
FROM USER_DRIVE_REQUESTS ur
LEFT JOIN SELECTED_ROUTE_OPTION chosen_route
    ON chosen_route.request_number = ur.number
WHERE ur.number = ? AND ur.user_username = ?
";

$stmt = sqlsrv_query($conn, $sql, [$request_number, $user]);
if ($stmt === false || !($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
    die("Request not found.");
}

$status = $row['status'];
$option_id = $row['option_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ride Request Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl p-6 border border-gray-200">

    <?php if ($status === "AwaitingDrivers"): ?>
        <h1 class="text-2xl font-bold text-indigo-700 mb-4">Awaiting Driver Responses</h1>
        <p class="text-gray-700 mb-6">
            Your selected route option has been sent to the drivers.  
            <br>Once <strong>all drivers accept</strong>, your ride will be ready to start.
        </p>

        <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-lg text-indigo-700">
            <p><strong>Request #:</strong> <?= htmlspecialchars($request_number) ?></p>
            <p><strong>Status:</strong> Waiting on driver responses…</p>
        </div>

        <a href="user_request_status.php?request=<?= $request_number ?>"
           class="mt-6 inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
            Refresh Status
        </a>

    <?php elseif ($status === "OptionFailed"): ?>
        <h1 class="text-2xl font-bold text-red-700 mb-4">Route Option Failed</h1>

        <p class="text-gray-700 mb-4">
            One or more drivers <strong>rejected</strong> your selected route option.
        </p>

        <div class="p-4 bg-red-50 border border-red-300 rounded-lg text-red-700 mb-6">
            <p><strong>Request #<?= htmlspecialchars($request_number) ?></strong></p>
            <p>This route option is no longer available.</p>
        </div>

        <form method="POST" action="choose_route_option.php">
            <input type="hidden" name="request_number"
                   value="<?= htmlspecialchars($request_number) ?>">

            <button class="px-5 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700">
                Choose Another Route Option
            </button>
        </form>

    <?php elseif ($status === "ReadyToStart"): ?>
        <h1 class="text-2xl font-bold text-green-700 mb-4">Ride Ready To Start</h1>

        <p class="text-gray-700">
            All drivers have accepted. You can now start the ride when ready.
        </p>

        <a href="user_drive_start.php?request=<?= $request_number ?>"
           class="mt-6 inline-block px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700">
            Go to Ride Start
        </a>

    <?php else: ?>
        <h1 class="text-xl font-bold text-gray-700 mb-4">Request Status</h1>

        <p class="text-gray-700">Current status: <strong><?= htmlspecialchars($status) ?></strong></p>

        <a href="dashboard.php"
           class="mt-6 inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg rounded hover:bg-indigo-700">
            Back to Dashboard
        </a>

    <?php endif; ?>

</div>

</body>
</html>
