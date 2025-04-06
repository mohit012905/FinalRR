<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db_connect.php"; 

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch profile details from both `shippers` and `shipperprofile` tables
$query = "
    SELECT 
        s.username AS name, 
        s.email, 
        s.phoneno AS phone, 
        sp.profile_photo, 
        sp.fullname, 
        sp.address, 
        sp.dob, 
        sp.age
    FROM shippers s
    LEFT JOIN shipperprofile sp ON s.s_id = sp.s_id
    WHERE s.s_id = ?
";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["error" => "Database error: " . $conn->error]);
    exit();
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();

     // Ensure correct image path
     if (!empty($userData['profile_photo'])) {
        $userData['profile_photo'] = "uploads/" . $userData['profile_photo']; // Adjust folder as needed
    } else {
        $userData['profile_photo'] = "default.png"; // Default profile picture
    }
    
    echo json_encode($userData, JSON_PRETTY_PRINT);
} else {
    echo json_encode(["error" => "User not found"]);
} 

$stmt->close();
$conn->close();
?>
