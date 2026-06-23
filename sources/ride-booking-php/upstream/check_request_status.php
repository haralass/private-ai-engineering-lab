<?php
// START: TEMPORARY FIX TO PREVENT HTML OUTPUT
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); 
ini_set('display_errors', 0);
// END: TEMPORARY FIX TO PREVENT HTML OUTPUT
// Set header for JSON response
header('Content-Type: application/json');
session_start();

$response = ['request_status' => 'Pending', 'assigned_driver' => null, 'message' => 'Waiting for server response...', 'error' => false];

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $response['error'] = true;
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

$request_id = $_GET['request_id'] ?? null;

if (!$request_id || !is_numeric($request_id)) {
    $response['error'] = true;
    $response['message'] = 'Invalid request ID.';
    echo json_encode($response);
    exit;
}

// --- SQL SERVER CONNECTION CONFIGURATION ---
$serverName = "mssql.cs.ucy.ac.cy"; 
$connectionOptions = array(
    "Database" => "ihadji07",
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

    // A. Check the main request status from USER_DRIVE_REQUESTS
    $sql_status = "
        SELECT 
            UDR.status, 
            UDR.estimated_cost,
            D.username AS driver_username, 
            V.reg_number AS vehicle_reg_number
        FROM USER_DRIVE_REQUESTS UDR
        LEFT JOIN DRIVERS D ON UDR.assigned_driver_username = D.username
        LEFT JOIN VEHICLE V ON D.username = V.driver_username AND UDR.service_type IN (SELECT service_type FROM VEHICLE_SERVICES WHERE vehicle_reg_number = V.reg_number)
        WHERE UDR.[number] = ?
    ";
    
    $params_status = array($request_id);
    $stmt = sqlsrv_query($conn, $sql_status, $params_status);

    if ($stmt === false || !sqlsrv_has_rows($stmt)) {
        $response['request_status'] = 'Failed';
        $response['message'] = 'Request ID not found or database error.';
    } else {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $current_status = $row['status'];
        $response['request_status'] = $current_status;

        if ($current_status === 'Accepted') {
            $response['assigned_driver'] = [
                'username' => $row['driver_username'],
                'vehicle_reg' => $row['vehicle_reg_number'],
                'estimated_cost' => number_format($row['estimated_cost'], 2)
            ];
            $response['message'] = 'Your driver has accepted the request.';

        } else if ($current_status === 'Offering') {
            // If still in 'Offering' state, check the last driver offer
            $sql_last_offer = "
                SELECT TOP 1 driver_username
                FROM REQUEST_OFFERS
                WHERE drive_request_number = ?
                ORDER BY offer_time DESC
            ";
            $stmt_offer = sqlsrv_query($conn, $sql_last_offer, $params_status);
            $offer_row = sqlsrv_fetch_array($stmt_offer, SQLSRV_FETCH_ASSOC);
            
            if ($offer_row) {
                 $response['message'] = 'Offering ride to driver ' . htmlspecialchars($offer_row['driver_username']) . '. Please wait...';
            } else {
                 $response['message'] = 'Searching for best available driver.';
            }

        } else if ($current_status === 'Failed') {
             $response['message'] = 'Your request has failed due to system error or no route found.';
        } else if ($current_status === 'Cancelled') {
             $response['message'] = 'Your request was cancelled by you.';
        } else {
            // Default pending state
            $response['request_status'] = 'Pending';
            $response['message'] = 'Status: ' . htmlspecialchars($current_status) . '. System processing...';
        }
    }
    sqlsrv_free_stmt($stmt);

} catch (Exception $e) {
    $response['error'] = true;
    $response['message'] = 'Server Error: ' . $e->getMessage();
} finally {
    if ($conn) {
        sqlsrv_close($conn);
    }
}

echo json_encode($response);