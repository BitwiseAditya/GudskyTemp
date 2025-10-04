<?php
// api/db.php
// Update these with the credentials from Hostinger control panel
$db_host = 'localhost';
$db_user = 'Aditya_intern';
$db_pass = 'Aditya*&2025#intern';
$db_name = 'Contact_Us';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    // In production you might not want to echo the raw error
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

// Set charset
$mysqli->set_charset("utf8mb4");
?>
