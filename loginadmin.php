<?php
session_start(); // Start session at the very beginning
include "db_connect.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Check password
        if ($password === $admin['password']) {
            $_SESSION["username"] = $admin["username"];  // ✅ Store username
            $_SESSION["admin_id"] = $admin["id"]; // ✅ Store admin ID (if available)
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid Password.');</script>";
        }
    } else {
        echo "<script>alert('Admin Not Found.');</script>";
    }

    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login - Route Rover</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="container">
    <div class="form-box">
        <div class="logo-container">
            <img src="logo-removebg-preview (1).png" alt="Logo" class="logo">
        </div>
        <h2>Admin Login</h2>
        <form id="loginForm" action="" method="POST" onsubmit="return validateLoginForm()">
            <!-- Username -->
            <div class="input-box">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
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
                <a href="regadmin.php" class="register-now">Don't have an account? Register Now</a>
            </div>
        </form>
    </div>
</div>

<script>
    function validateLoginForm() {
        let username = document.getElementById("username").value;
        let password = document.getElementById("password").value;
        let valid = true;
        
        // Clear all error messages
        document.getElementById("loginUsernameError").innerHTML = "";
        document.getElementById("loginPasswordError").innerHTML = "";

        // Username validation (minimum 3 characters)
        if (username.length < 3) {
            document.getElementById("loginUsernameError").innerHTML = "Username must be at least 3 characters.";
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
