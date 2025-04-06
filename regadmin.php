<?php
session_start();
include "db_connect.php"; // Include DB connection

define("ADMIN_SECRET_KEY", "MOHIT_2005"); // Securely store in env or config file

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize inputs
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = trim($_POST['password']);  // No hashing
    $confirmPassword = trim($_POST['confirm_password']);
    $phone = htmlspecialchars($_POST['phone']);
    $entered_secret = trim($_POST['secret_key']);

    // Debugging Output
    echo "Entered Secret: " . $entered_secret . "<br>";
    echo "Expected Secret: " . ADMIN_SECRET_KEY . "<br>";

    // Check if Secret Key is correct
    if ($entered_secret !== ADMIN_SECRET_KEY) {
        echo "<script>alert('Unauthorized Access!'); window.location.href='regadmin.php';</script>";
        exit();
    }

    // Validate Password Match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!'); window.location.href='regadmin.php';</script>";
        exit();
    }

    // Prepare SQL Query
    $sql = "INSERT INTO admin (username, email, phone, password, secret_key) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $secret_key = ADMIN_SECRET_KEY; // Assign constant to a variable
    $stmt->bind_param("sssss", $username, $email, $phone, $password, $secret_key);

    if ($stmt->execute()) {
        echo "<script>alert('Admin Registered Successfully!'); window.location.href='loginadmin.php';</script>";
    } else {
        echo "<script>alert('Error: Could not register admin. Please try again.');</script>";
    }

    $stmt->close();
}
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Registration - Route Rover</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        input {
            width: 90%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Registration</h2>
    <form id="registerForm" action="regadmin.php" method="POST" onsubmit="return validateForm()">
        <input type="text" id="username" name="username" placeholder="Enter Username" required><br>
        <span class="error" id="usernameError"></span>

        <input type="email" id="email" name="email" placeholder="Enter Email" required><br>
        <span class="error" id="emailError"></span>

        <input type="tel" id="phone" name="phone" placeholder="Enter Phone Number" required><br>
        <span class="error" id="phoneError"></span>

        <input type="password" id="password" name="password" placeholder="Enter Password" required><br>
        <span class="error" id="passwordError"></span>

        <input type="password" id="confirmPassword" name="confirm_password" placeholder="Confirm Password" required><br>
        <span class="error" id="confirmPasswordError"></span>

        <input type="text" id="secretKey" name="secret_key" placeholder="Enter Secret Key" required><br>
        <span class="error" id="secretKeyError"></span>
        
        <button type="submit">Register</button>
        <div class="links">
                <a href="loginadmin.php" class="log-in">Already registered? Log-in</a>
            </div>
    </form>
</div>

<script>
    function validateForm() {
        let username = document.getElementById("username").value;
        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("confirmPassword").value;
        let phone = document.getElementById("phone").value;
        let secretKey = document.getElementById("secretKey").value;
        let valid = true;

        document.getElementById("usernameError").innerHTML = "";
        document.getElementById("emailError").innerHTML = "";
        document.getElementById("passwordError").innerHTML = "";
        document.getElementById("confirmPasswordError").innerHTML = "";
        document.getElementById("phoneError").innerHTML = "";
        document.getElementById("secretKeyError").innerHTML = "";

        if (username.length < 3) {
            document.getElementById("usernameError").innerHTML = "Username must be at least 3 characters.";
            valid = false;
        }

        let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(email)) {
            document.getElementById("emailError").innerHTML = "Invalid email address.";
            valid = false;
        }

        let phonePattern = /^[0-9]{10}$/;
        if (!phonePattern.test(phone)) {
            document.getElementById("phoneError").innerHTML = "Phone number must be exactly 10 digits.";
            valid = false;
        }

        if (password.length < 6) {
            document.getElementById("passwordError").innerHTML = "Password must be at least 6 characters.";
            valid = false;
        }

        if (password !== confirmPassword) {
            document.getElementById("confirmPasswordError").innerHTML = "Passwords do not match.";
            valid = false;
        }

        if (secretKey.length === 0) {
            document.getElementById("secretKeyError").innerHTML = "Secret Key is required.";
            valid = false;
        }

        return valid;
    }
</script>

</body>
</html>
