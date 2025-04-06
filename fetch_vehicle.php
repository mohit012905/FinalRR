<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

include "db_connect.php";

header("Content-Type: application/json"); // Ensure JSON response

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "status" => "error",
        "error" => "User not logged in",
        "debug_session" => $_SESSION // Debugging session data
    ]);
    exit();
}

$t_id = (int) $_SESSION["user_id"]; // Ensure it's an integer

// Validate transporter ID
if ($t_id <= 0) {
    echo json_encode(["status" => "error", "error" => "Invalid transporter ID"]);
    exit();
}

// Query to fetch vehicle details for the transporter
$query = "
    SELECT 
        vehicle_register_no, 
        vehicle_type, 
        vehicle_tyre, 
        route_permission 
    FROM vehicles 
    WHERE t_id = ?
";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["status" => "error", "error" => "Database error: " . $conn->error]);
    exit();
}

$stmt->bind_param("i", $t_id);
$stmt->execute();
$result = $stmt->get_result();

$vehicles = [];

while ($row = $result->fetch_assoc()) {
    $vehicles[] = $row;
}

if (!empty($vehicles)) {
    echo json_encode(["status" => "success", "vehicles" => $vehicles]);
} else {
    echo json_encode(["status" => "error", "error" => "No vehicles found"]);
}

$stmt->close();
$conn->close();
?>

