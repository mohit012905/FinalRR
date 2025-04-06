<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["user_id"])) {
    echo "<script>alert('You are not logged in!'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION["user_id"];

$query = "SELECT message, created_at, t_id, id FROM notifications WHERE s_id = ? ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        body {
            background-image: url('noti.jpg');
            background-size: cover;
            background-position: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            backdrop-filter: blur(3px);
        }

        .container {
            margin: 60px auto;
            max-width: 700px;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .heading {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #343a40;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 25px;
            text-align: center;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left: 5px solid #007bff;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-text {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }

        .timestamp {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-accept {
            background-color: #28a745;
            color: white;
        }

        .btn-accept:hover {
            background-color: #218838;
        }

        .btn-reject {
            background-color: #dc3545;
            color: white;
        }

        .btn-reject:hover {
            background-color: #c82333;
        }

        .no-notify {
            text-align: center;
            color: #6c757d;
            font-size: 18px;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="shipperdashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
    <h2 class="heading">📢 Your Notifications</h2>

    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $notification): ?>
            <div class="card">
                <p class="card-text"><?= htmlspecialchars($notification['message']) ?></p>
                <p class="timestamp">📅 <?= date("F j, Y, g:i a", strtotime($notification['created_at'])) ?></p>
                <div class="btn-group">
                <button class="btn btn-accept" onclick="fetchTransporterDetails(<?= $notification['t_id'] ?>)" >✅ Accept</button>
                <button class="btn btn-reject" onclick="rejectNotification(<?= $notification['id'] ?>, this)">❌ Reject</button>

                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-notify">No notifications yet.</p>
    <?php endif; ?>
</div>
<!-- Transporter Modal -->
<div id="transporterModal" style="display: none; position: fixed; top: 0; left: 0; 
     width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center;">
  <div style="background: white; padding: 20px 30px; border-radius: 12px; max-width: 400px; text-align: center;">
    <h3>🚛 Transporter Info</h3>
    <p id="modalName"></p>
    <p id="modalPhone"></p>
    <p id="modalEmail"></p>
    <p>Would you like to pay now or later?</p>
    <button onclick="handlePayNow()" style="background: #28a745; color: white; padding: 10px 15px; margin: 5px; border: none; border-radius: 5px;">💳 Pay Now</button>
    <button onclick="handlePayLater()" style="background: #ffc107; color: black; padding: 10px 15px; margin: 5px; border: none; border-radius: 5px;">⏳ Pay Later</button>
    <br><br>
    <button onclick="closeModal()" style="margin-top: 10px; background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 5px;">Close</button>
  </div>
</div>

<script>
function fetchTransporterDetails(t_id) {
    fetch("get_transporter_details.php?t_id=" + t_id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate modal
                document.getElementById("modalName").textContent = "👤 Name: " + data.username;
                document.getElementById("modalPhone").textContent = "📞 Phone: " + data.phone_no;
                document.getElementById("modalEmail").textContent = "✉️ Email: " + data.email;

                // Show modal
                document.getElementById("transporterModal").style.display = "flex";
            } else {
                alert("❌ " + data.message);
            }
        })
        .catch(error => {
            console.error("Fetch error:", error);
            alert("❌ Failed to fetch transporter details.");
        });
}
function closeModal() {
    document.getElementById("transporterModal").style.display = "none";
}

function handlePayNow() {
    closeModal();
    alert("💳 Redirecting to payment page...");
     window.location.href = 'payment.php'; // Uncomment if you have a payment page
}

function handlePayLater() {
    closeModal();
    alert("⏳ Payment deferred. You can pay later from your dashboard.");
    // You can also send a backend request here if needed
}


function rejectNotification(notificationId, buttonElement) {
    if (!confirm("Are you sure you want to reject this notification?")) return;

    fetch("delete_notification.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("❌ Notification removed!");
            // Remove the card from UI
            const card = buttonElement.closest('.card');
            if (card) card.remove();
        } else {
            alert("⚠️ Error: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error deleting notification:", error);
        alert("⚠️ Something went wrong.");
    });
}
</script>
</body>
</html>
