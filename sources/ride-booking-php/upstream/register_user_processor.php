<?php
// =========================================================================================
// NOTE: This script requires the 'sqlsrv' PHP extension to be installed and enabled.
// This is the official Microsoft driver for connecting PHP to SQL Server.
// =========================================================================================

// --- 1. SQL SERVER CONNECTION CONFIGURATION ---
// IMPORTANT: Replace these placeholder values with your actual server details.
$serverName = "mssql.cs.ucy.ac.cy"; // e.g., "localhost\SQLEXPRESS" or server IP
$connectionOptions = array(
    "Database" => "mssql.cs.ucy.ac.cy", // Your database name
    "Uid" => "ihadji07",
    "PWD" => "h6uXaQ4c"   // The password for the database user
);
// --- END CONFIGURATION ---

// Check if the form was actually submitted
if (isset($_POST['register'])) {

    // --- 2. INPUT VALIDATION AND SANITIZATION ---

    // Sanitize and trim inputs
    $email = trim($_POST['dbName'] ?? '');
    $username = trim($_POST['userName'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['pswd'] ?? '';
    $password_confirm = $_POST['pswd2'] ?? '';

    $errors = [];

    // Basic validation checks
    if (empty($email) || empty($username) || empty($address) ||empty($password) || empty($password_confirm)) {
        $errors[] = "All fields are required.";
    }

    if ($password !== $password_confirm) {
        $errors[] = "The two passwords you entered do not match.";
    }

    // Check if any validation failed
    if (!empty($errors)) {
        echo "<h2>Registration Failed</h2>";
        echo "<div style='color: red; border: 1px solid red; padding: 15px; background-color: #ffeaea;'>";
        foreach ($errors as $error) {
            echo "<p>- " . htmlspecialchars($error) . "</p>";
        }
        echo "</div>";
        exit;
    }
    
    // --- 3. SECURITY: HASH THE PASSWORD ---
    // NEVER store raw passwords. password_hash() is the secure standard.
    // the default algorithm is currently BCRYPT.
    // the length of the resulting hash will always be 60 characters.
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // --- 4. CONNECT TO DATABASE ---
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if ($conn === false) {
        // Log the error and display a user-friendly message
        echo "<h2>Database Connection Error</h2>";
        echo "<p>Could not connect to the database. Check the server name and credentials in the script.</p>";
        // For debugging only, print the actual SQL errors
        error_log(print_r(sqlsrv_errors(), true)); 
        exit;
    }

    // --- 5. EXECUTE PARAMETERIZED INSERT QUERY ---
    
    // The query uses '?' placeholders. GETDATE() is a SQL Server function for the current time.
    $sql = "INSERT INTO [USERS] ([username], [email], [password_hash], [address]) 
            VALUES (?, ?, ?, ?)";
    
    // Parameters array: values must be in the same order as the placeholders in the SQL statement
    $params = array($username, $email, $passwordHash, $address);

    // Prepare and execute the statement
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    // --- 6. HANDLE RESULT ---
    if ($stmt === false) {
        // If query execution failed, check for errors (e.g., duplicate unique key violation)
        $error_info = sqlsrv_errors();
        $display_message = "Registration failed due to a database error.";

        // Attempt to check if it's a common duplicate key error (SQLSTATE 23000)
        if (isset($error_info[0]['SQLSTATE']) && $error_info[0]['SQLSTATE'] == '23000') {
            $display_message = "A user with this username or email already exists. Please choose different ones.";
        }
        
        echo "<h2>Registration Failed!</h2>";
        echo "<div style='color: red; border: 1px solid red; padding: 15px; background-color: #ffeaea;'>";
        echo "<p>" . htmlspecialchars($display_message) . "</p>";
        echo "</div>";
    } else {
        // Success
        echo "<h2>Registration Successful!</h2>";
        echo "<p style='color: green;'>Welcome, " . htmlspecialchars($username) . ". You have been successfully added to the system.</p>";
    }

    // --- 7. CLEANUP ---
    // Free statement resources and close the connection
    if (isset($stmt)) {
        sqlsrv_free_stmt($stmt);
    }
    sqlsrv_close($conn);

} else {
    // If accessed directly without form submission
    echo "<h1>Access Denied</h1>";
    echo "<p>This page should only be accessed by submitting the registration form.</p>";
}
?>