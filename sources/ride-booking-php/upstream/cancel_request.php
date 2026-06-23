<?php
// Set header for JSON response
header('Content-Type: application/json');
session_start();

$response = ['status' => 'error', 'message' => ''];

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}
$user_username = $_SESSION['username'];

$request_id = $_POST['request_id'] ?? null;

if (!$request_id || !is_numeric($request_id)) {
    $response['message'] = 'Invalid request ID.';
    echo json_encode($response);
    exit;
}

// --- SQL SERVER CONNECTION CONFIGURATION ---
$serverName = "mssql.cs.ucy.ac.cy"; 
$connectionOptions = array(
    "Database" => "mssql.cs.ucy.ac.cy",
    "Uid" => "ihadji07",
    "PWD" => "h6uXaQ4c"
);
// --- END CONFIGURATION ---

$conn = null;

try {
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if ($conn === false) {
        throw new Exception("Database connection failed.");
    }
    
    // Begin transaction for safe update
    if (sqlsrv_begin_transaction($conn) === false) {
        throw new Exception("Could not start transaction.");
    }

    // 1. Update the main request status to 'Cancelled'
    $sql_cancel = "
        UPDATE USER_DRIVE_REQUESTS
        SET status = 'Cancelled'
        WHERE [number] = ? 
          AND username = ? -- Security check: only the requesting user can cancel
          AND status IN ('Pending', 'Offering') -- Can only cancel if not already Accepted/Failed
    ";
    
    $params_cancel = array($request_id, $user_username);
    $stmt = sqlsrv_query($conn, $sql_cancel, $params_cancel);

    if ($stmt === false) {
        sqlsrv_rollback($conn);
        throw new Exception("Database error during cancellation.");
    }
    
    $rows_affected = sqlsrv_rows_affected($stmt);
    sqlsrv_free_stmt($stmt);
    
    if ($rows_affected > 0) {
        // 2. Mark any pending offers as 'Cancelled'
        $sql_cancel_offers = "
            UPDATE REQUEST_OFFERS
            SET [status] = 'Cancelled'
            WHERE drive_request_number = ? AND [status] = 'Pending'
        ";
        sqlsrv_query($conn, $sql_cancel_offers, array($request_id));
        
        sqlsrv_commit($conn);
        $response['status'] = 'success';
        $response['message'] = 'Request successfully cancelled.';
    } else {
        sqlsrv_rollback($conn);
        $response['message'] = 'Request could not be cancelled. It may have already been accepted, failed, or you are not the requester.';
    }

} catch (Exception $e) {
    if (sqlsrv_has_rows($stmt)) { sqlsrv_rollback($conn); }
    $response['message'] = 'Error: ' . $e->getMessage();
} finally {
    if ($conn) {
        sqlsrv_close($conn);
    }
}

echo json_encode($response);