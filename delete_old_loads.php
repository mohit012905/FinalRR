<?php
$host = "localhost";
$user = "root"; 
$password = ""; 
$database = "routerover";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Delete records older than 7 days
$sql = "DELETE FROM loads WHERE created_at < NOW() - INTERVAL 7 DAY";
$conn->query($sql);

$conn->close();
?>
