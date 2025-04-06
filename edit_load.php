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

$load_id = $_GET['id'];

// Fetch load data
$sql = "SELECT * FROM loads WHERE load_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $load_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "Load not found!";
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $s_id = $_POST['s_id'];
    $pickup = $_POST['pickup'];
    $drop = $_POST['drop'];
    $material = $_POST['material'];
    $quantity = $_POST['quantity'];
    $vehicletype = $_POST['vehicletype'];
    $truckbody = $_POST['truckbody'];
    $tyre = $_POST['tyre'];
    $paymentmethod = $_POST['paymentmethod'];

    $update = "UPDATE loads SET s_id=?, pickup=?, `drop`=?, material=?, quantity=?, vehicletype=?, truckbody=?, tyre=?, paymentmethod=? WHERE load_id=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("isssissssi", $s_id, $pickup, $drop, $material, $quantity, $vehicletype, $truckbody, $tyre, $paymentmethod, $load_id);

    if ($stmt->execute()) {
        echo "<script>alert('Load updated successfully.'); window.location.href='admin_dashboard.php';</script>";
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Load</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Edit Load Details</h2>
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label>Shipper ID</label>
            <input type="number" name="s_id" class="form-control" value="<?= htmlspecialchars($row['s_id']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Pickup Location</label>
            <input type="text" name="pickup" class="form-control" value="<?= htmlspecialchars($row['pickup']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Drop Location</label>
            <input type="text" name="drop" class="form-control" value="<?= htmlspecialchars($row['drop']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Material</label>
            <input type="text" name="material" class="form-control" value="<?= htmlspecialchars($row['material']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Quantity</label>
            <input type="text" name="quantity" class="form-control" value="<?= htmlspecialchars($row['quantity']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Vehicle Type</label>
            <input type="text" name="vehicletype" class="form-control" value="<?= htmlspecialchars($row['vehicletype']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Truck Body</label>
            <input type="text" name="truckbody" class="form-control" value="<?= htmlspecialchars($row['truckbody']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Tyre</label>
            <input type="text" name="tyre" class="form-control" value="<?= htmlspecialchars($row['tyre']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Payment Method</label>
            <input type="text" name="paymentmethod" class="form-control" value="<?= htmlspecialchars($row['paymentmethod']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
