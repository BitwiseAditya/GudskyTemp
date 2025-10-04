<?php
// public_html/api/contact.php
// Minimal endpoint: accepts POST (JSON or form) and saves to contacts table

// --- Optional: enable for debugging while you test ---
// Uncomment the two lines below temporarily if you want PHP errors shown in the response.
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
// -----------------------------------------------------

header("Content-Type: application/json; charset=UTF-8");

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
    exit;
}

// Include DB connection (adjust path if you moved db.php)
require_once __DIR__ . '/db.php';

// Read input: support JSON (fetch) or form-encoded (classic form)
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) $input = $_POST;

// Trim & fetch fields
$fullName = isset($input['fullName']) ? trim($input['fullName']) : '';
$email    = isset($input['email']) ? trim($input['email']) : '';
$phone    = isset($input['phone_number']) ? trim($input['phone_number']) : '';
$message  = isset($input['message']) ? trim($input['message']) : '';

// Basic validation
$errors = [];
if ($fullName === '') $errors[] = "Full name is required.";
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
if ($phone === '') $errors[] = "Phone number is required.";
if ($message === '') $errors[] = "Message is required.";

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(["success" => false, "errors" => $errors]);
    exit;
}

// Insert into DB (prepared statement)
$stmt = $mysqli->prepare("INSERT INTO contacts (full_name, email, phone, message) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    // Log server-side and return generic message to client
    error_log("DB prepare failed: " . $mysqli->error);
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$stmt->bind_param("ssss", $fullName, $email, $phone, $message);

if (!$stmt->execute()) {
    error_log("DB execute failed: " . $stmt->error);
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to save message"]);
    $stmt->close();
    $mysqli->close();
    exit;
}

$insertedId = $stmt->insert_id;
$stmt->close();
$mysqli->close();

// Success response
http_response_code(201);
echo json_encode([
    "success" => true,
    "message" => "Message received. Thank you!",
    "id" => $insertedId
]);
exit;
?>


