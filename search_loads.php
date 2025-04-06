<?php
include 'db_connect.php'; // Include your database connection file

header('Content-Type: application/json');

if (isset($_GET['pickup']) && isset($_GET['destination'])) {
    $pickup = trim($_GET['pickup']);
    $destination = trim($_GET['destination']); // This should map to `drop` in DB

    if (empty($pickup) || empty($destination)) {
        echo json_encode(["status" => "error", "message" => "Both pickup and destination are required"]);
        exit;
    }

    // Prepare SQL query to fetch loads based on pickup and drop location
    $stmt = $conn->prepare("SELECT pickup, `drop`, material, quantity, vehicletype, truckbody, tyre, paymentmethod 
                            FROM loads WHERE pickup = ? AND `drop` = ?");
    $stmt->bind_param("ss", $pickup, $destination);
    $stmt->execute();
    $result = $stmt->get_result();

    $loads = [];
    while ($row = $result->fetch_assoc()) {
        $loads[] = $row;
    }

    if (count($loads) > 0) {
        echo json_encode(["status" => "success", "loads" => $loads]);
    } else {
        echo json_encode(["status" => "error", "message" => "No loads found for this route"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
