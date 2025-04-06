<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$t_id = $_SESSION['user_id']; // Get logged-in user's transporter ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_register_no = $_POST['vehicle_register_no'];
    $vehicle_type = $_POST['vehicle_type'];
    $vehicle_tyre = $_POST['vehicle_tyre'];
    $route_permission = $_POST['route_permission'];

    // Insert into database
    $sql = "INSERT INTO vehicles (t_id, vehicle_register_no, vehicle_type, vehicle_tyre, route_permission) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issis", $t_id, $vehicle_register_no, $vehicle_type, $vehicle_tyre, $route_permission);

    if ($stmt->execute()) {
        echo "<script>alert('Vehicle details uploaded successfully!'); window.location.href='Transdashboard.php?page=vehicles';</script>";
    } else {
        echo "<script>alert('Error uploading vehicle details.');</script>";
    }
    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Vehicle Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('truckk.jpg'); /* Replace with your actual image path */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 40%;
            background: rgba(255, 255, 255, 0.9);
            padding: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            text-align: left;
            margin-top: 10px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            font-size: 16px;
            cursor: pointer;
            border: none;
            padding: 10px;
            margin-top: 10px;
        }
        .upload-btn {
            background-color: #007bff;
            color: white;
        }
        .upload-btn:hover {
            background-color: #0056b3;
        }
        .back-btn {
            background-color: #dc3545;
            color: white;
        }
        .back-btn:hover {
            background-color: #b02a37;
        }
    </style>
    <script>
        function validateVehicleNumber() {
            var regNo = document.getElementById("vehicle_register_no").value;
            var pattern = /^[A-Z]{2}-\d{2}-[A-Z]{2}-\d{4}$/; // Format: GJ-05-AB-1234

            if (!pattern.test(regNo)) {
                alert("Invalid Vehicle Number! Format: GJ-05-AB-1234");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Upload Vehicle Details</h2>
    <form method="POST" action="" onsubmit="return validateVehicleNumber()">
        <label>Vehicle Register No (Format: GJ-05-AB-1234):</label>
        <input type="text" id="vehicle_register_no" name="vehicle_register_no" placeholder="E.g., GJ-05-AB-1234" required>

        <label>Vehicle Type:</label>
        <select name="vehicle_type" required>
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

        <label>Vehicle Tyre:</label>
        <input type="number" name="vehicle_tyre" min="4" max="18" required>

        <label>Route Permission:</label>
        <select name="route_permission" required>
            <option value="Gujarat-Rajasthan">Gujarat - Rajasthan</option>
            <option value="Maharashtra-Karnataka">Maharashtra - Karnataka</option>
            <option value="Delhi-Punjab">Delhi - Punjab</option>
            <option value="Uttar Pradesh-Bihar">Uttar Pradesh - Bihar</option>
            <option value="Tamil Nadu-Kerala">Tamil Nadu - Kerala</option>
            <option value="West Bengal-Odisha">West Bengal - Odisha</option>
            <option value="All India">All India</option>
        </select>

        <button type="submit" class="upload-btn">Upload</button>
    </form>
    
    <button onclick="window.location.href='Transdashboard.php'" class="back-btn">Back to Dashboard</button>
</div>

</body>
</html>
