<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$serverName = "mssql.cs.ucy.ac.cy";
$connectionOptions = [
    "Database" => "ihadji07",
    "Uid" => "ihadji07",
    "PWD" => "h6uXaQ4c"
];
$conn = sqlsrv_connect($serverName,$connectionOptions);

$request = intval($_GET["request"]);

// -----------------------------------------------------
// 1. Fetch request + detect if previous option failed
// -----------------------------------------------------
$sql_req = "
    SELECT 
        number,
        status,
        user_username,
        chosen_option_id
    FROM USER_DRIVE_REQUESTS
    WHERE number = ?
";
$stmt_req = sqlsrv_query($conn, $sql_req, [$request]);
$request_row = sqlsrv_fetch_array($stmt_req, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt_req);

if (!$request_row) {
    die("Invalid request.");
}

$failed = ($request_row['status'] === 'OptionFailed');

// -----------------------------------------------------
// 2. Fetch route options for this request
// -----------------------------------------------------
$sql = "
SELECT *
FROM ROUTE_OPTIONS
WHERE drive_request_number = ?
ORDER BY ranking_score ASC
";
$stmt = sqlsrv_query($conn, $sql, [$request]);

$options = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $options[] = $row;
}
sqlsrv_free_stmt($stmt);

// -----------------------------------------------------
?>
<!DOCTYPE html>
<html>
<head>
<title>Select Route Option</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-3xl font-bold mb-6 text-indigo-800">
    Select Route Option for Request #<?= $request ?>
</h1>

<?php if ($failed): ?>
    <div class="bg-red-100 border border-red-300 text-red-800 p-4 rounded-lg mb-6">
        <h2 class="font-bold text-xl mb-1">⚠ A driver rejected your previous option</h2>
        <p>Please choose another available route option.</p>
    </div>
<?php endif; ?>


<?php if (empty($options)): ?>
    <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 p-4 rounded-lg">
        No route options available.  
        Please adjust your request or try again later.
    </div>
<?php else: ?>

    <?php foreach ($options as $opt): ?>
        <div class="bg-white shadow p-5 mb-6 rounded-lg border border-gray-200">
            
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">
                Option #<?= $opt["id"] ?>
            </h2>

            <p class="text-gray-700 mb-4">
                <strong>Total Distance:</strong> <?= $opt["total_distance_km"] ?> km  
                <br>
                <strong>Ranking Score:</strong> <?= $opt["ranking_score"] ?>
            </p>

            <h3 class="font-bold text-gray-900 mt-3 mb-2">Segments:</h3>

            <?php
            $stmt2 = sqlsrv_query(
                $conn,
                "SELECT * FROM ROUTE_OPTION_SEGMENTS WHERE route_option_id=? ORDER BY segment_number ASC",
                [$opt["id"]]
            );
            ?>

            <div class="space-y-2">
            <?php while ($seg = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)): ?>
                <div class="p-3 bg-gray-50 rounded border">
                    <div><strong>Segment:</strong> <?= $seg["segment_number"] ?></div>
                    <div>Driver: <span class="font-semibold"><?= $seg["driver_username"] ?></span></div>
                    <div>Vehicle: <span class="font-semibold"><?= $seg["vehicle_reg_number"] ?></span></div>
                    <div>Distance: <?= $seg["segment_distance_km"] ?> km</div>
                </div>
            <?php endwhile; ?>
            </div>

            <?php sqlsrv_free_stmt($stmt2); ?>

            <form method="POST" action="confirm_route_option.php" class="mt-5">
                <input type="hidden" name="request_number" value="<?= $request ?>">
                <input type="hidden" name="option_id" value="<?= $opt["id"] ?>">

                <button 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-lg shadow">
                    Choose this option
                </button>
            </form>
        </div>
    <?php endforeach; ?>

<?php endif; ?>

</body>
</html>
