<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "routerover";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = $_POST['payment_method'];
    $card_number = $_POST['card_number'] ?? null;
    $cardholder_name = $_POST['cardholder_name'] ?? null;
    $expiry_date = $_POST['expiry_date'] ?? null;
    $cvv = $_POST['cvv'] ?? null;
    $upi_id = $_POST['upi_id'] ?? null;
    $bank_name = $_POST['bank_name'] ?? null;
    $wallet = $_POST['wallet'] ?? null;
    $amount = $_POST['amount'];

    $sql = "INSERT INTO payments (payment_method, card_number, cardholder_name, expiry_date, cvv, upi_id, bank_name, wallet, amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssd", $payment_method, $card_number, $cardholder_name, $expiry_date, $cvv, $upi_id, $bank_name, $wallet, $amount);
    
    if ($stmt->execute()) {
        echo "<script>alert('Payment successful!'); window.location.href='shipperdashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='payment_page.html';</script>";
    }
    
    $stmt->close();
}

$conn->close();
?>
