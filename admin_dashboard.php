<?php
session_start();
include "db_connect.php"; // Ensure database connection is included

if (!isset($_SESSION["username"])) {  
    echo "<script>alert('Unauthorized access!'); window.location.href='loginadmin.php';</script>";
    exit();
}

// Fetch data
$sql_shipper = "SELECT * FROM shippers";
$result_shipper = $conn->query($sql_shipper);

$sql_truck = "SELECT * FROM truck";
$result_truck = $conn->query($sql_truck);

$sql_loads = "SELECT * FROM loads";
$result_loads = $conn->query($sql_loads);

$sql_vehicles = "SELECT * FROM vehicles";
$result_vehicles = $conn->query($sql_vehicles);

$sql_payments = "SELECT * FROM payments";
$result_payments = $conn->query($sql_payments);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<style>
    body {
        background-color: #eef2f7;
        background-image: url('lll.jpg'); /* Replace with your actual image path */
        background-size: cover;
        background-position: center;
        font-family: 'Poppins', sans-serif;
    }

    .container {
        margin-top: 50px;
    }

    h2 {
        font-weight: bold;
        color: #333;
    }

    .card {
        border-radius: 10px;
        background: linear-gradient(to right, #007bff, #00c6ff);
        color: white;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-custom {
        padding: 12px 25px;
        border-radius: 8px;
        font-size: 16px;
        transition: 0.3s ease-in-out;
        font-weight: 600;
    }

    .btn-primary {
        background: linear-gradient(to right, #007bff, #00c6ff);
        border: none;
        color: white;
        box-shadow: 0 3px 6px rgba(0, 123, 255, 0.4);
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #0056b3, #0084ff);
        transform: scale(1.05);
    }

    .btn-danger {
        background: linear-gradient(to right, #dc3545, #ff4f5a);
        border: none;
        color: white;
        box-shadow: 0 3px 6px rgba(220, 53, 69, 0.4);
    }

    .btn-danger:hover {
        background: linear-gradient(to right, #a71d2a, #ff1f3f);
        transform: scale(1.05);
    }

    .table-container {
        margin-top: 30px;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        display: none; /* Initially hidden */
        transition: all 0.3s ease-in-out;
    }

    .table {
        margin-top: 15px;
        border-radius: 10px;
        overflow: hidden;
    }

    .table thead {
        background: linear-gradient(to right, #007bff, #00c6ff);
        color: white;
        font-weight: bold;
    }

    .table tbody tr {
        transition: all 0.3s ease-in-out;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
    }

    th, td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        font-size: 16px;
    }

    td {
        font-size: 15px;
    }

    button {
        cursor: pointer;
    }

    /* Add User Dropdown Styles */
    .btn-group {
        margin: 20px 0;
    }

    .btn-success.dropdown-toggle {
        background: linear-gradient(to right, #28a745, #00c851);
        border: none;
        font-weight: 500;
        font-size: 16px;
        padding: 10px 20px;
        border-radius: 8px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        color: white;
        box-shadow: 0 3px 6px rgba(40, 167, 69, 0.4);
    }

    .btn-success.dropdown-toggle:hover {
        background: linear-gradient(to right, #218838, #00b34a);
        transform: scale(1.05);
    }

    .dropdown-menu {
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        animation: fadeIn 0.3s ease-in-out;
        padding: 0.5rem 0;
    }

    .dropdown-menu .dropdown-item {
        font-size: 15px;
        padding: 10px 20px;
        transition: background-color 0.2s ease;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f1f1f1;
        color: #000;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>



<div class="container">
    <h2>Welcome, Admin <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>!</h2>
    <a href="loginadmin.php" class="btn btn-danger">Logout</a>
    <button id="manageShipperBtn" class="btn btn-primary">Manage Shippers</button>
    <button id="managetruckBtn" class="btn btn-primary">Manage Trucks</button>
    <button id="manageLoadsBtn" class="btn btn-primary">Manage Loads</button>
    <button id="manageVehiclesBtn" class="btn btn-primary">Manage Vehicles</button>
    <button id="managePaymentsBtn" class="btn btn-primary">Manage Payments</button>
    <!-- Add User Dropdown -->
<div class="btn-group">
    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        Add User
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="add_shipper.php">Add Shipper</a></li>
        <li><a class="dropdown-item" href="add_transporter.php">Add Transporter</a></li>
    </ul>
</div>

    <!-- Shipper Table -->
    <div id="shipperTableContainer" class="table-container">
        <h3>Shipper Data</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Password</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_shipper->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['s_id'] ?></td>
                        <td><?= $row['username'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['phoneno'] ?></td>
                        <td><?= $row['password'] ?></td>
                        <td>
    <a href="edit_shipper.php?s_id=<?= $row['s_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
    <button onclick="deleteRecord('shippers', 's_id', <?= $row['s_id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Truck Table -->
    <div id="truckTableContainer" class="table-container">
        <h3>Truck Data</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Password</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_truck->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['t_id'] ?></td>
                        <td><?= $row['username'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['phoneno'] ?></td>
                        <td><?= $row['password'] ?></td>
                        <td>
                        <a href="edit_truck.php?id=<?= $row['t_id'] ?>" class="btn btn-warning btn-sm">Edit</a>

                            <button onclick="deleteRecord('truck', 't_id', <?= $row['t_id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Loads Table -->
    <div id="loadsTableContainer" class="table-container">
        <h3>Loads Data</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Load ID</th>
                    <th>Shipper ID</th>
                    <th>Pickup</th>
                    <th>Drop</th>
                    <th>Material</th>
                    <th>Quantity</th>
                    <th>Vehicle Type</th>
                    <th>Truck Body</th>
                    <th>Tyre</th>
                    <th>Payment Method</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_loads->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['load_id'] ?></td>
                        <td><?= $row['s_id'] ?></td>
                        <td><?= $row['pickup'] ?></td>
                        <td><?= $row['drop'] ?></td>
                        <td><?= $row['material'] ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td><?= $row['vehicletype'] ?></td>
                        <td><?= $row['truckbody'] ?></td>
                        <td><?= $row['tyre'] ?></td>
                        <td><?= $row['paymentmethod'] ?></td>
                        <td>
                        <a href="edit_load.php?id=<?= $row['load_id'] ?>" class="btn btn-warning btn-sm">Edit</a>

                            <button onclick="deleteRecord('loads', 'load_id', <?= $row['load_id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Vehicles Table -->
<div id="vehiclesTableContainer" class="table-container">
        <h3>Vehicles Data</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Transporter ID</th>
                    <th>Vehicle Register No</th>
                    <th>Vehicle Type</th>
                    <th>Vehicle Tyre</th>
                    <th>Route Permission</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_vehicles->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['t_id'] ?></td>
                        <td><?= $row['vehicle_register_no'] ?></td>
                        <td><?= $row['vehicle_type'] ?></td>
                        <td><?= $row['vehicle_tyre'] ?></td>
                        <td><?= $row['route_permission'] ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                        <a href="edit_vehicle.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>

                            <button onclick="deleteRecord('vehicles', 'id', <?= $row['id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
 <!-- Payments Table -->
 <div id="paymentsTableContainer" class="table-container">
        <h3>Payments Data</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Payment Method</th>
                    <th>Card Number</th>
                    <th>Cardholder Name</th>
                    <th>Expiry Date</th>
                    <th>CVV</th>
                    <th>UPI ID</th>
                    <th>Bank Name</th>
                    <th>Wallet</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_payments->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['payment_method'] ?></td>
                        <td><?= $row['card_number'] ?></td>
                        <td><?= $row['cardholder_name'] ?></td>
                        <td><?= $row['expiry_date'] ?></td>
                        <td><?= $row['cvv'] ?></td>
                        <td><?= $row['upi_id'] ?></td>
                        <td><?= $row['bank_name'] ?></td>
                        <td><?= $row['wallet'] ?></td>
                        <td><?= $row['amount'] ?></td>
                        <td><?= $row['payment_date'] ?></td>
                        <td>
                            <button onclick="deleteRecord('payments', 'id', <?= $row['id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>

function deleteRecord(table, column, id) {
    if (confirm("Are you sure you want to delete this record?")) {
        window.location.href = `delete.php?table=${table}&column=${column}&id=${id}`;
    }
}

const buttonToTableMap = {
    "manageShipperBtn": "shipperTableContainer",
    "managetruckBtn": "truckTableContainer",
    "manageLoadsBtn": "loadsTableContainer",
    "manageVehiclesBtn": "vehiclesTableContainer",
    "managePaymentsBtn": "paymentsTableContainer"
};

// Add click listeners for all buttons
for (const [buttonId, tableId] of Object.entries(buttonToTableMap)) {
    document.getElementById(buttonId).addEventListener("click", () => toggleTable(tableId));
}

function toggleTable(showId) {
    // Hide all tables first
    const tables = document.querySelectorAll(".table-container");
    tables.forEach(table => {
        if (table.id === showId) {
            // Toggle only the selected one
            table.style.display = (table.style.display === "block") ? "none" : "block";
        } else {
            table.style.display = "none";
        }
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
