<?php
session_start();
include "db_connect.php"; // Ensure this file contains $conn for DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Secure query using prepared statements
    $sql = "SELECT t_id, password FROM truck WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Direct password comparison (not recommended)
        if ($password === $row["password"]) {  
            $_SESSION["user_id"] = $row["t_id"]; // Store user ID in session

            echo "<script>alert('Login successful!'); window.location.href='Transdashboard.php';</script>";
            exit(); 
        } else {
            echo "<script>alert('Invalid password.'); window.location.href='loginshipper.php';</script>";
        }
    } else {
        echo "<script>alert('User not found.'); window.location.href='logintruck.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>TruckOwner Login - Route Rover</title>
    <link rel="stylesheet" href="truck.css">
</head>
<body>

<div class="container">
    <div class="form-box">
        <div class="logo-container">
            <img src="logo-removebg-preview (1).png" alt="Logo" class="logo">
        </div>
        <h2>TruckOwner Login</h2>
        <form id="loginForm" action="logintruck.php" method="POST" onsubmit="return validateLoginForm()">
            <!-- Username -->
            <div class="input-box">
                <label for="email">E-mail</label>
                <input type="text" id="email" name="email" placeholder="Enter your E-mail" required>
                <span class="error" id="loginUsernameError"></span>
            </div>
            
            <!-- Password -->
            <div class="input-box">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <span class="error" id="loginPasswordError"></span>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" class="submit-btn">Login</button>
            
            <!-- Forgot Password and Register Links -->
            <div class="links">
                <a href="#" class="forgot-password">Forgot Password?</a>
                <a href="regtruck.php" class="register-now">Don't have an account? Register Now</a>
            </div>
        </form>
    </div>
</div>

<script>
    function validateLoginForm() {
        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;
        let valid = true;
        
        // Clear all error messages
        document.getElementById("loginemailError").innerHTML = "";
        document.getElementById("loginPasswordError").innerHTML = "";

        // Username validation (minimum 3 characters)
        if (username.length < 3) {
            document.getElementById("loginemailError").innerHTML = "E-mail must be at least 10 characters.";
            valid = false;
        }

        // Password validation (minimum 6 characters)
        if (password.length < 6) {
            document.getElementById("loginPasswordError").innerHTML = "Password must be at least 6 characters.";
            valid = false;
        }

        return valid;  // return false if validation fails
    }
</script>

</body>
</html>
