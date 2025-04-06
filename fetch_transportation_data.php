<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "routerover");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch all truck records and their associated vehicles
$sql = "SELECT t.username, t.email, t.phoneno, v.vehicle_type, v.route_permission 
        FROM truck t
        LEFT JOIN vehicles v ON t.t_id = v.id"; // Ensure the correct foreign key relationship

$result = $conn->query($sql);

// Debugging: Check for SQL errors
if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Phone No</th>
                <th>Truck Type</th>
                <th>Route Permission</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['username']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phoneno']}</td>
                <td>" . (!empty($row['vehicle_type']) ? $row['vehicle_type'] : "N/A") . "</td>
                <td>" . (!empty($row['route_permission']) ? $row['route_permission'] : "N/A") . "</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No data found</p>";
}

$conn->close();
?>
