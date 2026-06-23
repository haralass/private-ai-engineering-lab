<?php
// Start the session at the very top of the script
session_start();

function verify_bcrypt($password, $hash) {
    return crypt($password, $hash) === $hash;
}

// Redirect if the user is already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

// ===================================================================
// Configuration: Update these with your actual database credentials
// NOTE: This must use the SQL Server connection method.
// ===================================================================
$serverName = "mssql.cs.ucy.ac.cy"; // e.g., "localhost\SQLEXPRESS" or server IP
$connectionOptions = array(
    "Database" => "ihadji07", // Your database name
    "Uid" => "ihadji07",
    "PWD" => "h6uXaQ4c"   // The password for the database user
);
// --- END CONFIGURATION ---

$message = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $message = "Please enter both username and password.";
    } else {
        // --- 1. Establish Database Connection ---
        $conn = sqlsrv_connect($serverName, $connectionOptions);
        if ($conn === false) {
            $message = "Database connection failed.";
            // For debugging: $message .= print_r(sqlsrv_errors(), true);
        } else {
            try {
                // 2. Fetch the user and their hashed password
                // Use TOP 1 for efficiency as username should be unique
                $sql = "SELECT TOP 1 [password_hash], [role] FROM USERS WHERE username = ?";
                $params = array($username);
                
                $stmt = sqlsrv_query($conn, $sql, $params);

                if ($stmt === false) {
                    throw new Exception("Query failed.");
                }

                $user_row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

                if ($user_row && verify_bcrypt($password, $user_row['password_hash'])) {
                    // 3. Successful Login: Set session variables
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $user_row['role'];
                    
                    // 4. Redirect to the dashboard
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $message = "Invalid username or password.";
                }

            } catch (Exception $e) {
                $message = "An error occurred during login. " . $e->getMessage();
            } finally {
                // Cleanup
                if (isset($stmt)) {
                    sqlsrv_free_stmt($stmt);
                }
                if ($conn) {
                    sqlsrv_close($conn);
                }
            }
        }
    }
}

// If login failed, redirect back to login page with an error message
if (!empty($message)) {
    // In a real application, you would pass the message via session or GET parameter
    // For simplicity, we'll assume the login form itself (login.php) will be updated
    // to include the processor and display the $message variable.
    // Since login.php is not the processor, we'll stop execution here and output the error.
    echo "<h1>Login Failed</h1>";
    echo "<p style='color: red;'>$message</p>";
    echo "<p><a href='login.php'>Try again</a></p>";
}
// End of PHP script
?>