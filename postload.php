<?php
session_start();
include 'db_connect.php'; // Ensure this file correctly connects to your database

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: loginshipper.php"); // Redirect to login page if not logged in
    exit();
}

$shipper_id = $_SESSION["user_id"]; // Get logged-in shipper's ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $pickup = $_POST['pickup'];
    $drop = $_POST['drop']; // Use backticks (`drop`) in SQL due to reserved keyword
    $material = $_POST['material'];
    $quantity = max(1, $_POST['quantity']); // Ensure quantity is at least 1 ton
    $vehicletype = $_POST['vehicletype'];
    $truckbody = $_POST['truckbody'];
    $tyre = $_POST['tyre'];
    $paymentmethod = $_POST['paymentmethod'];

    // Prepare SQL statement
    $sql = "INSERT INTO loads (s_id, pickup, `drop`, material, quantity, vehicletype, truckbody, tyre, paymentmethod) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("isssissis", $shipper_id, $pickup, $drop, $material, $quantity, $vehicletype, $truckbody, $tyre, $paymentmethod);

        // Execute statement
        if ($stmt->execute()) {
            echo "<script>alert('Load posted successfully!'); window.location.href = 'shipperdashboard.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Load</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: url('bg-image.jpg') no-repeat center center/cover;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
        }
        button {
            width: 48%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .post-btn {
            background: #0057ff;
            color: white;
        }
        .post-btn:hover {
            background: #003fcc;
        }
        .back-btn {
            background: #ccc;
            color: black;
        }
        .back-btn:hover {
            background: #999;
        }
        .pay-btn {
            background: #ccc;
            color: black;
        }
        .pay-btn:hover {
            background: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Post Load</h2>
        <form method="POST">
            <input type="hidden" name="s_id" value="<?php echo $_SESSION['user_id']; ?>"> 
            <input type="text" name="pickup" placeholder="Pickup Location" required>
            <input type="text" name="drop" placeholder="Drop Location" required>

            <select name="material" required>
                <option value="">Select Material</option>
                <option value="Coal">Coal</option>
                <option value="Cement">Cement</option>
                <option value="Steel">Steel</option>
                <option value="Food Grains">Food Grains</option>
                <option value="Chemicals">Chemicals</option>
                <option value="Electronics">Electronics</option>
                <option value="Furniture">Furniture</option>
                <option value="Machinery">Machinery</option>
                <option value="Oil & Gas">Oil & Gas</option>
                <option value="Construction Material">Construction Material</option>
            </select>

            <input type="number" name="quantity" placeholder="Quantity (in tons)" min="1" required>

            <select name="vehicletype" required>
                <option value="">Select Vehicle Type</option>
                <option value="Truck">Truck</option>
                <option value="Trailer">Trailer</option>
                <option value="Tanker">Tanker</option>
                <option value="Pickup Van">Pickup Van</option>
                <option value="Container Truck">Container Truck</option>
                <option value="Mini Truck">Mini Truck</option>
                <option value="Flatbed Truck">Flatbed Truck</option>
                <option value="Refrigerated Truck">Refrigerated Truck</option>
                <option value="Dumper Truck">Dumper Truck</option>
            </select>

            <select name="truckbody" required>
                <option value="" disabled selected>Select Truck Body</option>
                <option value="Open">Open</option>
                <option value="Closed">Closed</option>
            </select>

            <!-- Tyre Options (4 to 18) -->
            <select name="tyre" required>
                <option value="" disabled selected>Select Tyre</option>
                <?php for ($i = 4; $i <= 18; $i += 2) { ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?> Tyres</option>
                <?php } ?>
            </select>

            <select name="paymentmethod" required>
                <option value="Cash">Advance</option>
                <option value="Online">To pay</option>
            </select>
            
            <div class="btn-container">
                <button type="submit" class="post-btn">Post Load</button>
                <button type="button" class="back-btn" onclick="window.location.href='shipperdashboard.php'">Back to Dashboard</button>
            </div>
        </form>
    </div>
</body>
</html>

