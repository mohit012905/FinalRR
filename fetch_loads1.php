<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: loginshipper.php"); // Redirect to login if not logged in
    exit();
}

include 'db_connect.php'; // Ensure this file contains the database connection

$s_id = $_SESSION['user_id']; // Get the logged-in user's shipper ID

// Fetch loads from the database for the logged-in user
$query = "SELECT pickup, `drop`, material, quantity,vehicletype,truckbody,tyre,paymentmethod FROM loads WHERE s_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $s_id);
$stmt->execute();
$result = $stmt->get_result();

$loads = [];

while ($row = $result->fetch_assoc()) {
    $loads[] = $row;
}

// Return data in JSON format
header("Content-Type: application/json");
echo json_encode($loads);
?>
