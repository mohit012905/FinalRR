<?php
include 'db_connect.php';
header('Content-Type: application/json');

$pickup = isset($_GET['pickup']) ? $_GET['pickup'] : '';
$destination = isset($_GET['destination']) ? $_GET['destination'] : '';

if (empty($pickup) || empty($destination)) {
    echo json_encode(["status" => "error", "message" => "Pickup and destination required"]);
    exit();
}

$query = "SELECT * FROM loads WHERE pickup = ? AND destination = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $pickup, $destination);
$stmt->execute();
$result = $stmt->get_result();

$loads = [];
while ($row = $result->fetch_assoc()) {
    $loads[] = $row;
}

if (count($loads) > 0) {
    echo json_encode(["status" => "success", "data" => $loads]);
} else {
    echo json_encode(["status" => "error", "message" => "No matching loads found"]);
}

$stmt->close();
$conn->close();
?>
