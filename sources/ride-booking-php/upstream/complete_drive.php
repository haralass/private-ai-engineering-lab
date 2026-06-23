<?php
session_start();

// Security: must be logged in and must be a driver
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['role'] ?? '') !== 'driver') {
    header('Location: login.php');
    exit;
}

$driver_username = $_SESSION['username'];
$message = '';
$message_class = 'error';

$serverName = "mssql.cs.ucy.ac.cy";
$connectionOptions = [
    "Database" => "ihadji07",
    "Uid"      => "ihadji07",
    "PWD"      => "h6uXaQ4c"
];

$request_number = filter_var($_POST['request_number'] ?? null, FILTER_VALIDATE_INT);

if (!$request_number) {
    $message = "Invalid drive request.";
    $message_class = 'error';
} else {
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if (!$conn) {
        $message = "Database connection failed.";
    } else {

        // ✔ Validate the request belongs to THIS driver
        $validate_sql = "
            SELECT assigned_driver_username, status
            FROM USER_DRIVE_REQUESTS
            WHERE number = ?
        ";
        $stmt_valid = sqlsrv_query($conn, $validate_sql, [$request_number]);

        if (!$stmt_valid || !sqlsrv_fetch($stmt_valid)) {
            $message = "Drive request not found.";
        } else {
            $assigned_driver = sqlsrv_get_field($stmt_valid, 0);
            $req_status      = sqlsrv_get_field($stmt_valid, 1);

            if ($assigned_driver !== $driver_username) {
                $message = "You are not assigned to this drive.";
            } elseif (!in_array($req_status, ['Accepted', 'In Progress', 'On-Drive'])) {
                $message = "This drive is not in an active state.";
            } else {
                // ✔ Call CompleteDrive stored procedure
                $sp_result = '';
                $tsql = "{CALL dbo.CompleteDrive(?, ?)}";

                $params = [
                    [$request_number, SQLSRV_PARAM_IN],
                    [&$sp_result, SQLSRV_PARAM_OUT, SQLSRV_PHPTYPE_STRING, SQLSRV_SQLTYPE_NVARCHAR]
                ];

                $stmt_sp = sqlsrv_query($conn, $tsql, $params);

                if ($stmt_sp === false) {
                    $err = sqlsrv_errors();
                    $message = "SQL Procedure Error: " . htmlspecialchars($err[0]['message'] ?? 'Unknown error');
                } else {
                    sqlsrv_next_result($stmt_sp);
                    $message = $sp_result;

                    if (stripos($sp_result, 'successfully') !== false) {
                        $message_class = 'success';
                    } else {
                        $message_class = 'error';
                    }
                }

                if ($stmt_sp) sqlsrv_free_stmt($stmt_sp);
            }
        }

        if ($stmt_valid) sqlsrv_free_stmt($stmt_valid);
        sqlsrv_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Drive Completion Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgb(0 0 0 / 10%); max-width: 500px; text-align: center; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; border-radius: 8px; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; border-radius: 8px; }
        a { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 6px; }
        a:hover { background: #0056b3; }
    </style>
</head>
<body>
<div class="box">
    <h2>Drive Completion Status</h2>
    <div class="<?php echo htmlspecialchars($message_class); ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
    <a href="driver_dashboard.php">Return to Dashboard</a>
</div>
</body>
</html>
