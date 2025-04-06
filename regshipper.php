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
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = isset($_POST['phoneno']) ? $_POST['phoneno'] : '';
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($password != $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Prevent SQL injection
        $username = $conn->real_escape_string($username);
        $email = $conn->real_escape_string($email);
        $phone = $conn->real_escape_string($phone);
        $password = $conn->real_escape_string($password);

        // Fix: Ensure password is stored correctly
        $sql = "INSERT INTO `shippers` (`username`, `email`, `phoneno`, `password`) VALUES ('$username', '$email', '$phone', '$password')";

        $result = $conn->query($sql);
        
        if ($result) {
            echo "<script>alert('Registration successful!'); window.location.href='loginshipper.php';</script>";
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shipper Registration - Route Rover</title>
    <link rel="stylesheet" href="shipper.css">
</head>
<body>

<div class="container">
    <div class="form-box">
        <div class="logo-container">
            <img src="logo-removebg-preview (1).png" alt="Logo" class="logo">
        </div>
        
        <h2>Shipper Registration</h2>
        <form id="registerForm" action="regshipper.php" method="POST" onsubmit="return validateForm()">
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

            <div class="input-box">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phoneno" name="phoneno" placeholder="Enter your phone number" required>
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
                <input type="password" id="confirmPassword" name="confirm-password" placeholder="Confirm your password" required>
                <span class="error" id="confirmPasswordError"></span>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" name="submit" class="submit-btn">Register</button>
            
            <!-- Forgot Password and Login Links -->
            <div class="links">
                <a href="loginshipper.php" class="log-in">Already registered? Log-in</a>
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
