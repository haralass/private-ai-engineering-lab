<?php
session_start();

// ------------------------------------------------------------
// AUTH CHECK
// ------------------------------------------------------------
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$username  = $_SESSION['username'];
$user_role = $_SESSION['role'] ?? 'simple user';

// ------------------------------------------------------------
// DATABASE CONNECTION
// ------------------------------------------------------------
$serverName = "mssql.cs.ucy.ac.cy";
$connectionOptions = [
    "Database"     => "ihadji07",
    "Uid"          => "ihadji07",
    "PWD"          => "h6uXaQ4c",
    "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

$activeRequest = null;
$active_request_id = null;  // for track button

if ($user_role === 'simple user' && $conn) {
    // --------------------------------------------------------
    // 1. Find latest *active* request for this user
    //    Active = Offering, WaitingForDriver, InProgress
    // --------------------------------------------------------
    $sqlReq = "
        SELECT TOP 1
            number,
            request_time,
            status,
            service_type,
            estimated_cost,
            pick_up_latitude,
            pick_up_longitude,
            destination_latitude,
            destination_longitude
        FROM USER_DRIVE_REQUESTS
        WHERE user_username = ?
          AND status IN ('Offering','WaitingForDriver','InProgress')
        ORDER BY request_time DESC, number DESC;
    ";

    $stmtReq = sqlsrv_query($conn, $sqlReq, [$username]);

    if ($stmtReq && ($row = sqlsrv_fetch_array($stmtReq, SQLSRV_FETCH_ASSOC))) {
        $activeRequest = $row;
    }
    sqlsrv_free_stmt($stmtReq);

    // --------------------------------------------------------
    // 2. If we have an active request, see if any segment is InProgress
    //    (to show the Track button)
    // --------------------------------------------------------
    if ($activeRequest) {
        $req_id = $activeRequest['number'];

        $sqlSeg = "
            SELECT TOP 1 1
            FROM DRIVES
            WHERE drive_request_number = ?
              AND status = 'InProgress';
        ";
        $stmtSeg = sqlsrv_query($conn, $sqlSeg, [$req_id]);
        $segmentActive = ($stmtSeg && sqlsrv_fetch_array($stmtSeg));
        if ($segmentActive) {
            $active_request_id = $req_id;
        }
        if ($stmtSeg) {
            sqlsrv_free_stmt($stmtSeg);
        }
    }
}

if ($conn) {
    sqlsrv_close($conn);
}

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans p-4">
<div class="container mx-auto mt-8 bg-white p-6 rounded-xl shadow-lg max-w-xl">
    <h1 class="text-3xl font-extrabold text-indigo-600 mb-4">
        Welcome, <?= h($username) ?>!
    </h1>

    <p class="text-gray-700 mb-6">
        You are logged in as
        <span class="font-semibold text-pink-600">
            <?= h($user_role) ?>
        </span>.
    </p>

    <?php if ($user_role === 'simple user'): ?>

        <!-- ==============================
             Latest Active Request (if any)
        =============================== -->
        <?php if ($activeRequest): ?>
            <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">
                    Your Latest Active Request #<?= h($activeRequest['number']) ?>
                </h2>
                <p class="text-sm text-gray-600 mb-1">
                    <strong>Status:</strong>
                    <span class="font-semibold text-indigo-700">
                        <?= h($activeRequest['status']) ?>
                    </span>
                </p>
                <p class="text-sm text-gray-600 mb-1">
                    <strong>Service:</strong> <?= h($activeRequest['service_type']) ?>
                </p>
                <p class="text-sm text-gray-600 mb-1">
                    <strong>Estimated Cost:</strong> <?= h($activeRequest['estimated_cost']) ?> €
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Requested at:
                    <?php
                    if ($activeRequest['request_time'] instanceof DateTime) {
                        echo h($activeRequest['request_time']->format('Y-m-d H:i'));
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </p>

                <div class="mt-4 space-y-2">
                    <!-- If still choosing route options -->
                    <?php if ($activeRequest['status'] === 'Offering'): ?>
                        <a href="choose_route_option.php?request=<?= h($activeRequest['number']) ?>"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 shadow">
                            🔀 View / Choose Route Options
                        </a>
                    <?php endif; ?>

                    <!-- If waiting for driver responses -->
                    <?php if ($activeRequest['status'] === 'WaitingForDriver'): ?>
                        <div class="text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded p-2">
                            We have sent offers to drivers for your selected route.  
                            Please wait while they respond.
                        </div>
                    <?php endif; ?>

                    <!-- If InProgress, track button appears below (global) -->
                </div>
            </div>
        <?php else: ?>
            <div class="mb-6 p-4 border border-blue-200 bg-blue-50 text-blue-800 rounded-lg">
                You have no active drive requests right now.
            </div>
        <?php endif; ?>

        <!-- ==============================
             Main actions for simple user
        =============================== -->
        <div class="space-y-4">
            <!-- Make Drive Request (always available) -->
            <a href="request_drive.php"
               class="w-full flex items-center justify-center px-6 py-3 text-base font-medium rounded-md
                      text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg transition">
                🚕 Make a Drive Request
            </a>

            <!-- Track driver (only if a segment is actually in progress) -->
            <?php if ($active_request_id !== null): ?>
                <a href="user_track_requests.php?request=<?= h($active_request_id) ?>"
                   class="w-full flex items-center justify-center px-6 py-3 text-base font-medium rounded-md
                          text-white bg-green-600 hover:bg-green-700 shadow-lg transition">
                    🚗 Track Your Driver
                </a>
            <?php endif; ?>
        </div>

    <?php elseif ($user_role === 'driver'): ?>

        <!-- Driver Portal link -->
        <div class="space-y-4">
            <a href="driver_dashboard.php"
               class="w-full flex items-center justify-center px-6 py-3 text-base font-medium rounded-md
                      text-white bg-green-500 hover:bg-green-600 shadow-lg transition">
                🚘 Go to Driver Portal
            </a>
        </div>

    <?php else: ?>
        <p class="text-gray-500">Your dashboard actions are not yet configured for this role.</p>
    <?php endif; ?>

    <div class="mt-6 border-t pt-4 flex justify-between items-center">
        <a href="logout.php"
           class="text-red-500 hover:text-red-700 font-medium transition">
            Log Out
        </a>
    </div>
</div>
</body>
</html>
