<?php
include 'db_connect.php';
header('Content-Type: application/json');

// Debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['t_id']) || !is_numeric($_GET['t_id'])) {
    echo json_encode(["success" => false, "message" => "Missing or invalid transporter ID"]);
    exit();
}

$t_id = intval($_GET['t_id']);

$query = "SELECT username, email, phoneno FROM truck WHERE t_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
    exit();
}

$stmt->bind_param("i", $t_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $transporter = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "username" => $transporter['username'],
        "email" => $transporter['email'],
        "phone_no" => $transporter['phoneno']
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Transporter not found for ID $t_id"]);
}

$stmt->close();
$conn->close();
?>
