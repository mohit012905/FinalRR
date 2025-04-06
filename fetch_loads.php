<?php
include 'db_connect.php'; // Ensure your DB connection is correct

header('Content-Type: application/json');

$query = "SELECT * FROM loads";
$result = mysqli_query($conn, $query);

$loads = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $loads[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $loads]);
} else {
    echo json_encode(["status" => "error", "message" => "No loads available"]);
}

mysqli_close($conn);
?>
