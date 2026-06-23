<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$vehicle_id = $_POST['vehicle_id'];

$upload_dir = "uploads/$vehicle_id/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$license_path   = $upload_dir . basename($_FILES['license_doc']['name']);
$insurance_path = $upload_dir . basename($_FILES['insurance_doc']['name']);

move_uploaded_file($_FILES['license_doc']['tmp_name'], $license_path);
move_uploaded_file($_FILES['insurance_doc']['tmp_name'], $insurance_path);

try {
    $pdo = new PDO("mysql:host=localhost;dbname=test", "root", "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        INSERT INTO vehicle_documents (vehicle_id, document_type, file_path, status)
        VALUES (:vid, 'license', :file1, 'submitted'),
               (:vid, 'insurance', :file2, 'submitted')
    ");

    $stmt->execute([
        ':vid'   => $vehicle_id,
        ':file1' => $license_path,
        ':file2' => $insurance_path
    ]);

    $update = $pdo->prepare("
        UPDATE vehicles
        SET documents_status = 'submitted'
        WHERE id = :vid
    ");

    $update->execute([':vid' => $vehicle_id]);

    echo "OK – Τα έγγραφα ανέβηκαν και η βάση ενημερώθηκε.";

} catch (PDOException $e) {
    echo "SQL ERROR: " . $e->getMessage();
}
?>
