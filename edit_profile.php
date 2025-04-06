<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$t_id = $_SESSION['user_id'];

$sql = "SELECT * FROM transporter_profiles WHERE t_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $t_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];

    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;

    if (!empty($_FILES['profile_photo']['name'])) {
        $target_dir = "uploads/";
        $filename = basename($_FILES["profile_photo"]["name"]); // Extract filename
        $profile_photo = $filename;
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_dir . $filename);
    } else {
        $profile_photo = $user['profile_photo'] ?? NULL;
    }

    // Check if user already has a profile
    $check_sql = "SELECT * FROM transporter_profiles WHERE t_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $t_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing profile
        $update_sql = "UPDATE transporter_profiles SET profile_photo=?, fullname=?, address=?, dob=?, age=? WHERE t_id=?";
        $stmt = $conn->prepare($update_sql);
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }
        $stmt->bind_param("ssssii", $profile_photo, $fullname, $address, $dob, $age, $t_id);
    } else {
        // Insert new profile
        $insert_sql = "INSERT INTO transporter_profiles (t_id, profile_photo, fullname, address, dob, age) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }
        $stmt->bind_param("issssi", $t_id, $profile_photo, $fullname, $address, $dob, $age);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='Transdashboard.php';</script>";
    } else {
        die("Execution failed: " . $stmt->error);
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
    <title>Edit Profile</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; background-image: url('editbg.jpg');  background-size: cover;
            background-position: center;}
        form { max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px;  background: rgba(210, 202, 202, 0.9); }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #28a745; color: white; padding: 10px; border: none; cursor: pointer; width: 100%; }
        img { max-width: 100px; border-radius: 50%; margin-bottom: 10px; }
    </style>
</head>
<body>
<a href="Transdashboard.php" style="display: inline-block; margin-bottom: 20px; text-decoration: none; padding: 10px 15px; background-color: #007bff; color: white; border-radius: 5px;">⬅️ Back to Dashboard</a>

<h2>Edit Profile</h2>
<form action="edit_profile.php" method="POST" enctype="multipart/form-data">
    <label>Profile Photo:</label><br>
    <img id="photoPreview" src="<?php echo $user['profile_photo'] ?? 'default.png'; ?>" alt="Profile Photo"><br>
    <input type="file" name="profile_photo" id="profile_photo"><br><br>

    <label>Full Name:</label>
    <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" required><br><br>

    <label>Address:</label>
    <textarea name="address" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea><br><br>

    <label>Date of Birth:</label>
    <input type="date" name="dob" id="dob" value="<?php echo $user['dob'] ?? ''; ?>" required><br><br>

    <label>Age:</label>
    <input type="number" name="age" id="age" value="<?php echo $user['age'] ?? ''; ?>" readonly><br><br>

    <button type="submit">Save Changes</button>
</form>

<script>
    document.getElementById("dob").addEventListener("change", function() {
        let dob = new Date(this.value);
        let today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        document.getElementById("age").value = age;
    });
</script>

</body>
</html>
