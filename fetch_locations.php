<?php
include 'db_connect.php';
header('Content-Type: application/json');

$pickup_query = "SELECT DISTINCT pickup FROM loads";
$destination_query = "SELECT DISTINCT destination FROM loads";

$pickup_result = mysqli_query($conn, $pickup_query);
$destination_result = mysqli_query($conn, $destination_query);

$pickup_locations = [];
$destination_locations = [];

while ($row = mysqli_fetch_assoc($pickup_result)) {
    $pickup_locations[] = $row['pickup'];
}

while ($row = mysqli_fetch_assoc($destination_result)) {
    $destination_locations[] = $row['destination'];
}

echo json_encode([
    "status" => "success",
    "pickup" => $pickup_locations,
    "destination" => $destination_locations
]);

mysqli_close($conn);
?>
