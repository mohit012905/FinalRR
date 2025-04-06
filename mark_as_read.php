<?php
session_start();
include 'db_connect.php'; // Ensure this file correctly establishes a database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["notification_id"])) {
    $notification_id = intval($_POST["notification_id"]); // Ensure it's an integer

    // Update the notification status to 'read'
    $query = "UPDATE notifications SET status = 'read' WHERE id = $notification_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: noti.php"); // Redirect back to the notifications page
        exit();
    } else {
        die("Error updating notification: " . mysqli_error($conn));
    }
} else {
    die("Invalid request.");
}
?>
