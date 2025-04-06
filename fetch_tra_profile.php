<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$user_id = (int) $_SESSION["user_id"]; // Ensure it's an integer

// Validate user ID
if ($user_id <= 0) {
    echo json_encode(["status" => "error", "error" => "Invalid user ID"]);
    exit();
}

// Query to fetch data from truck and transporter_profiles tables
$query = "
    SELECT 
        t.username AS name, 
        t.email, 
        t.phoneno AS phone, 
        tp.profile_photo, 
        tp.fullname, 
        tp.address, 
        tp.dob, 
        tp.age
    FROM truck t
    LEFT JOIN transporter_profiles tp ON t.t_id = tp.t_id
    WHERE t.t_id = ?
";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["status" => "error", "error" => "Database error: " . $conn->error]);
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

    echo json_encode(["status" => "success", "data" => $userData]);
} else {
    echo json_encode(["status" => "error", "error" => "User not found"]);
}

$stmt->close();
$conn->close();
?>
