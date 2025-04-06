<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "You are not logged in!"]);
    exit();
}

$t_id = $_SESSION["user_id"]; // ✅ Get transporter ID from session

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['load_id']) || !isset($data['s_id'])) {
    echo json_encode(["success" => false, "message" => "Invalid or missing data"]);
    exit();
}

$load_id = $data['load_id'];
$s_id = $data['s_id'];
$message = "Your posted load is Accepted";  // ✅ Store "Accepted" as the message

// Insert notification into the database
$query = "INSERT INTO notifications (s_id, t_id, load_id, message, created_at) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
    exit();
}

$stmt->bind_param("iiis", $s_id, $t_id, $load_id, $message);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Notification added successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to save notification."]);
}

$stmt->close();
$conn->close();
?>
