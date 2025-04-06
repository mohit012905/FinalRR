<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["t_id"])) {
    echo json_encode(["status" => "error", "message" => "Transporter not logged in"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['load_id'])) {
        echo json_encode(["status" => "error", "message" => "Missing load ID"]);
        exit();
    }

    $load_id = $_POST['load_id'];
    $t_id = $_SESSION["t_id"]; // Automatically fetch transporter ID from session

    // Fetch shipper ID from loads table
    $query = "SELECT s_id FROM loads WHERE load_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $load_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Load not found"]);
        exit();
    }

    $row = $result->fetch_assoc();
    $s_id = $row['s_id'];

    // Insert booking notification
    $message = "Your load has been booked by a transporter.";
    $status = "unread";
    $insertQuery = "INSERT INTO notifications (s_id, t_id, load_id, message, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt_insert = $conn->prepare($insertQuery);
    $stmt_insert->bind_param("iiiss", $s_id, $t_id, $load_id, $message, $status);

    if ($stmt_insert->execute()) {
        echo json_encode(["status" => "success", "message" => "Booking confirmed and notification sent."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to send booking notification"]);
    }

    $stmt->close();
    $stmt_insert->close();
    $conn->close();
}
?>
