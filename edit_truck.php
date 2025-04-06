<?php
include "db_connect.php";
session_start();

if (!isset($_SESSION["username"])) {
    echo "<script>alert('Unauthorized access!'); window.location.href='loginadmin.php';</script>";
    exit();
}

// Check for truck ID
if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit();
}

$t_id = $_GET['id'];

// Fetch current truck data
$sql = "SELECT * FROM truck WHERE t_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $t_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "Truck not found!";
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phoneno = $_POST['phoneno'];
    $password = $_POST['password'];

    $update = "UPDATE truck SET username=?, email=?, phoneno=?, password=? WHERE t_id=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ssssi", $username, $email, $phoneno, $password, $t_id);

    if ($stmt->execute()) {
        echo "<script>alert('Truck details updated successfully.'); window.location.href='admin_dashboard.php';</script>";
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Truck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Edit Truck Details</h2>
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="username" class="form-label">Full Name</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($row['username']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="phoneno" class="form-label">Phone Number</label>
            <input type="text" name="phoneno" class="form-control" value="<?= htmlspecialchars($row['phoneno']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="text" name="password" class="form-control" value="<?= htmlspecialchars($row['password']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
