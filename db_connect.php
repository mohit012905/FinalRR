<?php
$servername = "localhost"; // Change if using a remote server
$username = "root"; // Default for local servers like XAMPP, WAMP
$password = ""; // Empty by default for local servers
$dbname = "routerover"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
