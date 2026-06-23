<?php
// =========================================================================================
// This script handles the driver registration form submission.
// It inserts data into the DRIVERS table and multiple entries into the DOCUMENTS table
// within a transaction to ensure data consistency.
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

// --- 1.1. FILE UPLOAD CONFIGURATION ---
$upload_dir = __DIR__ . '/uploads/driver_documents/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}
// --- END FILE CONFIGURATION ---


// --- 1.2. HELPER FUNCTION FOR UPLOAD ---
/**
 * Handles the file upload and returns the saved file path, or false on error.
 */
function handle_document_upload($file_key, $upload_dir, &$errors) {
    if (empty($_FILES[$file_key]['name'])) {
        $errors[] = "Document file is missing for key: $file_key.";
        return false;
    }

    $file = $_FILES[$file_key];
    $allowed_types = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    $max_file_size = 5 * 1024 * 1024; // 5 MB

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "File upload failed for $file_key with error code: " . $file['error'];
        return false;
    }
    
    if ($file['size'] > $max_file_size) {
        $errors[] = "File size for $file_key exceeds 5MB limit.";
        return false;
    }

    if (!in_array($file['type'], $allowed_types)) {
        $errors[] = "Invalid file type for $file_key. Only PDF, JPG, and PNG are allowed.";
        return false;
    }
    
    // Generate a unique filename to prevent overwriting and malicious file names
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_file_name = uniqid('doc_') . '_' . time() . '.' . $file_extension;
    $target_file = $upload_dir . $new_file_name;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        // Return the path relative to the script for database storage
        return 'uploads/driver_documents/' . $new_file_name; 
    } else {
        $errors[] = "Failed to move uploaded file for $file_key.";
        return false;
    }
}
// --- END HELPER FUNCTION ---

// Check if the driver registration form was submitted
if (isset($_POST['register_driver'])) {
    
    // --- 2. INPUT VALIDATION AND SANITIZATION (Driver Personal Info) ---
    // ... (Existing input handling logic goes here) ...
    $email = trim($_POST['dbName'] ?? '');
    $username = trim($_POST['userName'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? ''); 
    $password = $_POST['pswd'] ?? '';
    $password_confirm = $_POST['pswd2'] ?? '';
    
    $errors = [];

    // Basic validation checks
    if (empty($email) || empty($username) || empty($password)) {
        $errors[] = "All main fields are required.";
    }
    if ($password !== $password_confirm) {
        $errors[] = "Passwords do not match.";
    }
    
    // Hash the password for security
    $password_hash = password_hash($password, PASSWORD_DEFAULT);


    // --- 3. INPUT GATHERING AND INITIAL VALIDATION (Documents) ---
    // Define the list of documents to process, matching the form field names
    $documents_to_process = [
        'Driver License' => [
            'number_key' => 'license_number',
            'publish_key' => 'license_date_of_publish',
            'expiry_key' => 'license_expiry_date',
            'file_key' => 'driver_license_file'
        ],
        'Police Clearance' => [
            'number_key' => 'police_clearance_number',
            'publish_key' => 'police_clearance_date_of_publish',
            'expiry_key' => 'police_clearance_expiry_date',
            'file_key' => 'police_clearance_file'
        ],
        'Medical Certificate' => [
            'number_key' => 'medical_certificate_number',
            'publish_key' => 'medical_certificate_date_of_publish',
            'expiry_key' => 'medical_certificate_expiry_date',
            'file_key' => 'medical_certificate_file'
        ],
        'Mental Health Certificate' => [
            'number_key' => 'mental_health_certificate_number',
            'publish_key' => 'mental_health_certificate_date_of_publish',
            'expiry_key' => 'mental_health_certificate_expiry_date',
            'file_key' => 'mental_health_certificate_file'
        ]
    ];

    $document_data = [];
    $uploaded_files_to_rollback = [];

    foreach ($documents_to_process as $doc_type => $keys) {
        $doc_number = trim($_POST[$keys['number_key']] ?? '');
        $doc_publish = trim($_POST[$keys['publish_key']] ?? '');
        $doc_expiry = trim($_POST[$keys['expiry_key']] ?? '');

        if (empty($doc_number) || empty($doc_publish) || empty($doc_expiry)) {
             $errors[] = "$doc_type: All number and date fields are required.";
             continue; // Skip processing this document
        }

        // Handle the file upload for the current document
        $file_path = handle_document_upload($keys['file_key'], $upload_dir, $errors);

        if ($file_path) {
            $document_data[] = [
                'type' => $doc_type,
                'number' => $doc_number,
                'publish' => $doc_publish,
                'expiry' => $doc_expiry,
                'path' => $file_path
            ];
            $uploaded_files_to_rollback[] = $file_path; // Track successful uploads
        } else {
            // If upload failed, the error is already in $errors
        }
    }

    if (!empty($errors)) {
        // Display errors and exit before database connection/transaction
        echo "<h2>Error!</h2>";
        foreach ($errors as $error) {
            echo "<p style='color: red;'>- " . htmlspecialchars($error) . "</p>";
        }
        // No database operation was started, so just exit
        exit;
    }

    // --- 4. CONNECT TO DATABASE ---
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if ($conn === false) {
        // Check for connection error
        die("Connection failed: " . print_r(sqlsrv_errors(), true));
    }

    // --- 5. BEGIN TRANSACTION ---
    if (sqlsrv_begin_transaction($conn) === false) {
        die("Could not begin transaction: " . print_r(sqlsrv_errors(), true));
    }
    
    $success = true; // Flag to track overall success
    
    // --- 6. DRIVER INSERTION (First Query) ---
    // The DRIVERS table is defined in Database.sql
    $status = 'offline'; // Default status for new drivers could be 'offline' or 'online'
    $latitude = 0.0000;
    $longitude = 0.0000;
    // Set document_status to indicate all documents have been submitted for review
    $document_status = 'Pending'; // Could be 'Pending', 'Reviewed', 'Preapproved', 'Approved', 'Rejected'

    $sql_driver = "INSERT INTO [DRIVERS] ([username], [email], [password_hash], [address], [date_of_birth], [latitude], [longitude], [document_verification_status], [status])
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $params_driver = array(
        $username,
        $email,
        $password_hash,
        $address,
        $date_of_birth,
        $latitude,
        $longitude,
        $document_status,
        $status
    );

    $stmt_driver = sqlsrv_query($conn, $sql_driver, $params_driver);
    
    if ($stmt_driver === false) {
        $success = false;
        error_log("Driver INSERT failed: " . print_r(sqlsrv_errors(), true));
    } else {
        // --- 7. DOCUMENT INSERTION (Looping Queries) ---
        // Assuming DOCUMENTS table has: [driver_username] (FK), [type], [number], [date_of_publish], [expiry_date], [file_path]
        $sql_document = "INSERT INTO DOCUMENTS ([driver_username], [document_type], [document_number], [date_of_publish], [expiry_date], [file_path]) VALUES (?, ?, ?, ?, ?, ?)";

        foreach ($document_data as $doc) {
            $params_document = array(
                $username,              // [driver_username] (Foreign Key)
                $doc['type'],           // [document_type] (e.g., 'Driver License')
                $doc['number'],         // [number]
                $doc['publish'],        // [date_of_publish]
                $doc['expiry'],         // [expiry_date]
                $doc['path']            // [file_path]
            );

            $stmt_document = sqlsrv_query($conn, $sql_document, $params_document);

            if ($stmt_document === false) {
                $success = false;
                error_log("Document INSERT failed for type '{$doc['type']}': " . print_r(sqlsrv_errors(), true));
                break; // Exit the loop on first failure
            }
            sqlsrv_free_stmt($stmt_document);
        }
        
        sqlsrv_free_stmt($stmt_driver);
    }
    
    // --- 8. COMMIT OR ROLLBACK ---
    if ($success) {
        // If all database queries succeeded, commit the transaction
        sqlsrv_commit($conn);
        $display_message = "Driver registration successful! Your application and documents are now pending review.";
        echo "<h2>Driver Registration Successful!</h2>";
        echo "<p style='color: green;'>" . htmlspecialchars($display_message) . "</p>";

    } else {
        // If any database query failed, roll back the transaction
        sqlsrv_rollback($conn);
        
        // --- ROLLBACK FILE UPLOADS ---
        // Clean up the files that were successfully moved to the permanent directory
        foreach ($uploaded_files_to_rollback as $file_path) {
            $full_path = __DIR__ . '/' . $file_path;
            if (file_exists($full_path)) {
                unlink($full_path); // Delete the uploaded file
            }
        }
        
        $error_info = sqlsrv_errors();
        $display_message = "Driver registration failed due to a database error.";

        // Attempt to check if it's a common duplicate key error (SQLSTATE 23000)
        if (isset($error_info[0]['SQLSTATE']) && $error_info[0]['SQLSTATE'] == '23000') {
            $display_message = "A driver with this username or email already exists. Please choose different ones.";
        }
        
        echo "<h2>Driver Registration Failed!</h2>";
        echo "<div style='color: red; border: 1px solid red; padding: 15px; background-color: #ffeaea;'>";
        echo "<p>" . htmlspecialchars($display_message) . "</p>";
        echo "<p>Please ensure all fields are correctly filled and try again. The database transaction was rolled back.</p>";
        echo "</div>";
    }

    // --- 9. CLEANUP ---
    sqlsrv_close($conn);
} else {
    echo "Access Denied: Form not submitted.";
}
?>