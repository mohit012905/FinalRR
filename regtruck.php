<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Leave empty if no password is set
$dbname = "routerover";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Prevent SQL injection
        $username = $conn->real_escape_string($username);
        $email = $conn->real_escape_string($email);
        $phone = $conn->real_escape_string($phone);
        $password = $conn->real_escape_string($password); // Escape password too

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO `truck` (`username`, `email`, `phoneno`, `password`) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $phone, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location.href='logintruck.php';</script>";
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>TruckOwner Registration - Route Rover</title>
    <link rel="stylesheet" href="truck.css">
</head>
<body>

<div class="container">
    <div class="form-box">
        <div class="logo-container">
            <img src="logo-removebg-preview (1).png" alt="Logo" class="logo">
        </div>
        
        <h2>TruckOwner Registration</h2>
        <form id="registerForm" action="regtruck.php" method="POST" onsubmit="return validateForm()">
            <!-- Username -->
            <div class="input-box">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
                <span class="error" id="usernameError"></span>
            </div>
            
            <!-- Email -->
            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <span class="error" id="emailError"></span>
            </div>

            <!-- Phone Number -->
            <div class="input-box">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                <span class="error" id="loginPhoneError"></span>
            </div>
            
            <!-- Password -->
            <div class="input-box">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <span class="error" id="passwordError"></span>
            </div>
            
            <!-- Confirm Password -->
            <div class="input-box">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                <span class="error" id="confirmPasswordError"></span>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" name="submit" class="submit-btn">Register</button>
            
            <!-- Forgot Password and Login Links -->
            <div class="links">
                <a href="logintruck.php" class="log-in">Already registered? Log-in</a>
            </div>
        </form>
    </div>
</div>

<script>
    function validateForm() {
    let username = document.getElementById("username").value.trim();
    let email = document.getElementById("email").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;
    let valid = true;

    // Clear previous errors
    document.getElementById("usernameError").innerHTML = "";
    document.getElementById("emailError").innerHTML = "";
    document.getElementById("loginPhoneError").innerHTML = "";
    document.getElementById("passwordError").innerHTML = "";
    document.getElementById("confirmPasswordError").innerHTML = "";

    // Username validation
    if (username.length < 3) {
        document.getElementById("usernameError").innerHTML = "Username must be at least 3 characters.";
        valid = false;
    }

    // Email validation
    let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailPattern.test(email)) {
        document.getElementById("emailError").innerHTML = "Invalid email format.";
        valid = false;
    }

    // Phone validation (10 digits)
    let phonePattern = /^\d{10}$/;
    if (!phonePattern.test(phone)) {
        document.getElementById("loginPhoneError").innerHTML = "Phone number must be exactly 10 digits.";
        valid = false;
    }

    // Password validation (minimum 6 characters)
    if (password.length < 6) {
        document.getElementById("passwordError").innerHTML = "Password must be at least 6 characters.";
        valid = false;
    }

    // Confirm password validation
    if (password !== confirmPassword) {
        document.getElementById("confirmPasswordError").innerHTML = "Passwords do not match.";
        valid = false;
    }

    return valid; // Prevent form submission if false
}

</script>

</body>
</html>
