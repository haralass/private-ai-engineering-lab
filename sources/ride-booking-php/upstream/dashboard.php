<?php
// Start the session
session_start();

// Check if the user is logged in. If not, redirect them to the login page.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$user_role = $_SESSION['role'] ?? 'simple user';

// ------------------------------------------------------------
// DATABASE CONNECTION (for track-driver button)
// ------------------------------------------------------------
$serverName = "mssql.cs.ucy.ac.cy";
$connectionOptions = [
    "Database" => "ihadji07",
    "Uid" => "ihadji07",
    "PWD" => "h6uXaQ4c"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

// Default: user cannot track any request
$active_request_id = null;

if ($user_role === 'simple user' && $conn) {
    // 1. Find user's request that is InProgress
    $sqlReq = "
        SELECT TOP 1 number
        FROM USER_DRIVE_REQUESTS
        WHERE user_username = ?
          AND status = 'InProgress'
        ORDER BY number DESC;
    ";
    $stmtReq = sqlsrv_query($conn, $sqlReq, [$username]);

    if ($stmtReq && ($row = sqlsrv_fetch_array($stmtReq, SQLSRV_FETCH_ASSOC))) {
        $req_id = $row["number"];

        // 2. Check if any segment of that request is currently InProgress
        $sqlSeg = "
            SELECT TOP 1 1
            FROM DRIVES
            WHERE drive_request_number = ?
              AND status = 'InProgress';
        ";

        $stmtSeg = sqlsrv_query($conn, $sqlSeg, [$req_id]);

        $segmentActive = ($stmtSeg && sqlsrv_fetch_array($stmtSeg));

        if ($segmentActive) {
            $active_request_id = $req_id; // show button!
        }

        sqlsrv_free_stmt($stmtSeg);
    }

    sqlsrv_free_stmt($stmtReq);
}

if ($conn) {
    sqlsrv_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans p-4">
    <div class="container mx-auto mt-8 bg-white p-6 rounded-xl shadow-lg max-w-lg">
        <h1 class="text-3xl font-extrabold text-indigo-600 mb-4">
            Welcome, <?php echo htmlspecialchars($username); ?>!
        </h1>

        <p class="text-gray-700 mb-6">
            You are logged in as 
            <span class="font-semibold text-pink-600">
                <?php echo htmlspecialchars($user_role); ?>
            </span>.
        </p>

        <div class="space-y-4">

            <?php if ($user_role === 'simple user'): ?>

                <!-- Make Drive Request -->
                <a href="request_drive.php" 
                   class="w-full flex items-center justify-center px-6 py-3 text-base font-medium rounded-md 
                          text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg transition">
                    🚕 Make a Drive Request
                </a>

                <!-- TRACK YOUR DRIVER (Only if active segment exists) -->
                <?php if ($active_request_id !== null): ?>
                    <a href="user_track_request.php?request=<?= $active_request_id ?>"
                       class="w-full flex items-center justify-center px-6 py-3 text-base font-medium rounded-md
                              text-white bg-green-600 hover:bg-green-700 shadow-lg transition">
                        🚗 Track Your Driver
                    </a>
                <?php endif; ?>

            <?php elseif ($user_role === 'driver'): ?>

                <!-- Driver Portal -->
                <a href="driver_dashboard.php" 
                   class="w-full flex items-center justify-center px-6 py-3 text-base font-medium rounded-md 
                          text-white bg-green-500 hover:bg-green-600 shadow-lg transition">
                    🚘 Go to Driver Portal
                </a>

            <?php else: ?>
                <p class="text-gray-500">Your dashboard actions are not yet configured.</p>
            <?php endif; ?>
        </div>

        <div class="mt-6 border-t pt-4">
            <a href="logout.php" 
               class="text-red-500 hover:text-red-700 font-medium transition">
                Log Out
            </a>
        </div>
    </div>
</body>
</html>
