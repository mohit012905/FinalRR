<?php
include "db_connect.php";
session_start();

if (!isset($_SESSION["username"])) {
    echo "<script>alert('Unauthorized access!'); window.location.href='loginadmin.php';</script>";
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit();
}

$vehicle_id = $_GET['id'];

// Fetch existing vehicle data
$sql = "SELECT * FROM vehicles WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehicle_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "Vehicle not found!";
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transporter_id = $_POST['t_id'];
    $register_no = $_POST['vehicle_register_no'];
    $vehicle_type = $_POST['vehicle_type'];
    $vehicle_tyre = $_POST['vehicle_tyre'];
    $route_permission = $_POST['route_permission'];

    $update = "UPDATE vehicles SET t_id=?, vehicle_register_no=?, vehicle_type=?, vehicle_tyre=?, route_permission=? WHERE vehicle_id=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("issssi", $transporter_id, $register_no, $vehicle_type, $vehicle_tyre, $route_permission, $vehicle_id);

    if ($stmt->execute()) {
        echo "<script>alert('Vehicle updated successfully.'); window.location.href='admin_dashboard.php';</script>";
        exit();
    } else {
        echo "Error updating vehicle: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Edit Vehicle Details</h2>
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label>Transporter ID</label>
            <input type="number" name="transporter_id" class="form-control" value="<?= htmlspecialchars($row['t_id']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Vehicle Register No</label>
            <input type="text" name="register_no" class="form-control" value="<?= htmlspecialchars($row['vehicle_register_no']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Vehicle Type</label>
            <input type="text" name="vehicle_type" class="form-control" value="<?= htmlspecialchars($row['vehicle_type']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Vehicle Tyre</label>
            <input type="text" name="vehicle_tyre" class="form-control" value="<?= htmlspecialchars($row['vehicle_tyre']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Route Permission</label>
            <input type="text" name="route_permission" class="form-control" value="<?= htmlspecialchars($row['route_permission']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
