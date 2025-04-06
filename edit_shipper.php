<?php
include "db_connect.php";
$s_id = $_GET['s_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $phoneno = $_POST["phoneno"];
    $password = $_POST["password"];

    $sql = "UPDATE shippers SET username=?, email=?, phoneno=?, password=? WHERE s_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $email, $phoneno, $password, $s_id);
    $stmt->execute();

    echo "<script>alert('Record updated successfully'); window.location.href='admin_dashboard.php';</script>";
    exit();
}

$sql = "SELECT * FROM shippers WHERE s_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $s_id);
$stmt->execute();
$result = $stmt->get_result();
$shipper = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Shipper</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Edit Shipper</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" value="<?= $shipper['username'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="<?= $shipper['email'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phoneno" value="<?= $shipper['phoneno'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="text" name="password" value="<?= $shipper['password'] ?>" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
