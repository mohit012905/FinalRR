<?php
header("Content-Type: application/json"); // Ensure JSON response

$host = "localhost";
$user = "root"; 
$password = ""; 
$database = "routerover";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// Allow only POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit();
}

// Validate required fields
$required_fields = ['s_id', 'pickup', 'destination', 'material', 'quantity', 'vehicle_type', 'truck_body', 'tyre', 'payment_method'];

foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        http_response_code(400);
        echo json_encode(["error" => "Missing or empty field: $field"]);
        exit();
    }
}

// Assign and sanitize values
$s_id = filter_var($_POST['s_id'], FILTER_VALIDATE_INT);
$pickup = trim($conn->real_escape_string($_POST['pickup']));
$destination = trim($conn->real_escape_string($_POST['destination']));
$material = trim($conn->real_escape_string($_POST['material']));
$quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
$vehicle_type = trim($conn->real_escape_string($_POST['vehicle_type']));
$truck_body = trim($conn->real_escape_string($_POST['truck_body']));
$tyre = filter_var($_POST['tyre'], FILTER_VALIDATE_INT);
$payment_method = trim($conn->real_escape_string($_POST['payment_method']));

// Check if inputs are valid
if (!$s_id || !$quantity || !$tyre) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid numeric values provided"]);
    exit();
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO loads (s_id, pickup, destination, material, quantity, vehicle_type, truck_body, tyre, payment_method) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to prepare SQL statement"]);
    exit();
}

$stmt->bind_param("isssissis", $s_id, $pickup, $destination, $material, $quantity, $vehicle_type, $truck_body, $tyre, $payment_method);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(["success" => "Load posted successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $stmt->error . " SQL: " . $stmt->sqlstate]);
}


// Close connections
$stmt->close();
$conn->close();
?>
