<?php
include "db_connect.php"; // Ensure database connection is included

if (isset($_GET['table']) && isset($_GET['column']) && isset($_GET['id'])) {
    $table = $_GET['table'];
    $column = $_GET['column'];
    $id = intval($_GET['id']); // Prevent SQL injection

    $sql = "DELETE FROM $table WHERE $column = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Record deleted successfully!'); window.history.back();</script>";
    } else {
        echo "<script>alert('Error deleting record!'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>
