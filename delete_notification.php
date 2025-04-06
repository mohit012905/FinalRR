<?php
include 'db_connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["success" => false, "message" => "Notification ID missing"]);
    exit();
}

$id = intval($data['id']);

$stmt = $conn->prepare("DELETE FROM notifications WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Notification deleted"]);
} else {
    echo json_encode(["success" => false, "message" => "Deletion failed"]);
}

$stmt->close();
$conn->close();
?>
