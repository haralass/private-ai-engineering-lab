<?php
session_start();

// Must be logged in AND a driver
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] !== 'driver') {
    header("Location: dashboard.php");
    exit;
}

$driver_username = $_SESSION['username'];

// Validate incoming POST fields
$required = ["offer_id", "request_number", "drive_segment_id", "vehicle_reg_number", "response"];
foreach ($required as $field) {
    if (!isset($_POST[$field])) {
        die("Missing required field: $field");
    }
}

$offer_id           = intval($_POST["offer_id"]);
$request_number     = intval($_POST["request_number"]);
$segment_id         = intval($_POST["drive_segment_id"]);
$vehicle_reg_number = $_POST["vehicle_reg_number"];
$response           = $_POST["response"]; // "Accepted" or "Rejected"

$serverName = "mssql.cs.ucy.ac.cy";
$connectionOptions = [
    "Database" => "ihadji07",
    "Uid" => "ihadji07",
    "PWD" => "h6uXaQ4c"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die("Database connection failed.");
}

/* ---------------------------------------------------------
   1. VERIFY OFFER BELONGS TO THIS DRIVER
--------------------------------------------------------- */
$sql = "
    SELECT 
        RO.driver_username,
        RO.status,
        RO.vehicle_reg_number,
        D.status AS segment_status,
        D.segment_order
    FROM REQUEST_OFFERS RO
    INNER JOIN DRIVES D ON D.id = RO.drive_segment_id
    WHERE RO.id = ?
      AND RO.drive_request_number = ?
      AND RO.drive_segment_id = ?
";

$stmt = sqlsrv_query($conn, $sql, [$offer_id, $request_number, $segment_id]);
$offer = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$offer) {
    die("Invalid offer.");
}
if ($offer['driver_username'] !== $driver_username) {
    die("Unauthorized access.");
}
if ($offer['status'] !== "Pending") {
    die("Offer already responded to.");
}

/* ---------------------------------------------------------
   2. DRIVER REJECTED OFFER
--------------------------------------------------------- */
if ($response === "Rejected") {

    $update = sqlsrv_query(
        $conn,
        "UPDATE REQUEST_OFFERS SET status='Rejected', response_time=GETDATE() WHERE id=?",
        [$offer_id]
    );

    header("Location: driver_dashboard.php?msg=rejected");
    exit;
}

/* ---------------------------------------------------------
   3. DRIVER ACCEPTED OFFER
--------------------------------------------------------- */

/* ---------------------------------------------------------
   (A) DRIVER MUST NOT BE CURRENTLY DRIVING
--------------------------------------------------------- */
$sqlCheck = "
    SELECT COUNT(*) AS active_count
    FROM DRIVES
    WHERE assigned_driver_username = ?
      AND status = 'InProgress'
";
$stmtCheck = sqlsrv_query($conn, $sqlCheck, [$driver_username]);
$active = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);

if ($active["active_count"] > 0) {
    die("Cannot accept: You are already on a drive.");
}

/* ---------------------------------------------------------
   (B) ENSURE THIS SEGMENT IS THE NEXT SEGMENT OF THE REQUEST
--------------------------------------------------------- */

$sqlMinSeg = "
    SELECT MIN(segment_order) AS next_seg
    FROM DRIVES
    WHERE drive_request_number = ?
      AND status IN ('Pending','Offering')
";
$stmtMin = sqlsrv_query($conn, $sqlMinSeg, [$request_number]);
$nextSeg = sqlsrv_fetch_array($stmtMin, SQLSRV_FETCH_ASSOC)['next_seg'];

if ($nextSeg != $offer['segment_order']) {
    die("Cannot accept: This is not the next available segment.");
}

/* ---------------------------------------------------------
   (C) FINAL ACCEPTANCE — UPDATE TABLES
--------------------------------------------------------- */

sqlsrv_begin_transaction($conn);

$ok1 = sqlsrv_query(
    $conn,
    "UPDATE REQUEST_OFFERS
     SET status='Accepted', response_time=GETDATE()
     WHERE id=?",
    [$offer_id]
);

$ok2 = sqlsrv_query(
    $conn,
    "UPDATE DRIVES
     SET status='Assigned',
         assigned_driver_username = ?
     WHERE id=?",
    [$driver_username, $segment_id]
);

if ($ok1 && $ok2) {
    sqlsrv_commit($conn);
} else {
    sqlsrv_rollback($conn);
    die("Database error. Offer not accepted.");
}

header("Location: driver_dashboard.php?msg=accepted");
exit;
