<?php
session_start();
include "db_connect.php"; // Ensure this file contains $conn for DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Secure query using prepared statements
    $sql = "SELECT s_id, password FROM shippers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Direct password comparison (not recommended)
        if ($password === $row["password"]) {  
            $_SESSION["user_id"] = $row["s_id"]; // Store user ID in session

            echo "<script>alert('Login successful!'); window.location.href='shipperdashboard.php';</script>";
            exit(); 
        } else {
            echo "<script>alert('Invalid password.'); window.location.href='loginshipper.php';</script>";
        }
    } else {
        echo "<script>alert('User not found.'); window.location.href='loginshipper.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shipper Login - Route Rover</title>
    <link rel="stylesheet" href="shipper.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <div class="logo-container">
                <img src="logo-removebg-preview (1).png" alt="Logo" class="logo">
            </div>
            <h2>Shipper Login</h2>
            <form id="loginForm" action="loginshipper.php" method="POST" onsubmit="return validateLoginForm()">
                <div class="input-box">
                    <label for="email">E-mail</label>
                    <input type="text" id="email" name="email" placeholder="Enter your email" required>
                    <span class="error" id="loginUsernameError"></span>
                </div>
                <div class="input-box">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <span class="error" id="loginPasswordError"></span>
                </div>
                <button type="submit" class="submit-btn">Login</button>
                <div class="links">
                    <a href="#" class="forgot-password">Forgot Password?</a>
                    <a href="regshipper.php" class="register-now">Don't have an account? Register Now</a>
                </div>
            </form>
        </div>
    </div>
    <script>
      function validateLoginForm() {
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let valid = true;

    document.getElementById("loginUsernameError").innerHTML = "";
    document.getElementById("loginPasswordError").innerHTML = "";

    if (email.length < 8) {
        document.getElementById("loginUsernameError").innerHTML = "Email must be at least 8 characters.";
        valid = false;
    }

    if (password.length < 6) {
        document.getElementById("loginPasswordError").innerHTML = "Password must be at least 6 characters.";
        valid = false;
    }

    return valid; // Prevents form submission if validation fails
}


    </script>
</body>
</html>
