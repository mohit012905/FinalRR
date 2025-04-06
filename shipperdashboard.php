<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: loginshipper.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipper Dashboard</title>
    <style>
       /* Global Styles */
body {
    display: flex;
    font-family: Arial, sans-serif;
    margin: 0;
    background: url('shipdashbg.jpg') no-repeat center center/cover; /* Set Background Image */
    min-height: 100vh;
}

/* Sidebar Styling */
.sidebar {
    width: 260px;
    background: linear-gradient(180deg, #0a0f3b, #0057ff); /* Stylish Gradient */
    color: white;
    padding: 20px;
    height: 100vh;
    position: fixed;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    box-shadow: 5px 0 15px rgba(0, 0, 0, 0.3); /* Subtle Shadow */
}

.sidebar a {
    display: flex;
    align-items: center;
    color: white;
    text-decoration: none;
    padding: 12px;
    margin: 5px 0;
    border-radius: 8px;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.2s;
}

.sidebar a:hover,
.sidebar a.active {
    background: rgba(255, 255, 255, 0.2); /* Transparent Effect */
    transform: scale(1.05);
}

/* Main Content */
.main-content {
    margin-left: 280px;
    padding: 20px;
    width: calc(100% - 280px);
    backdrop-filter: blur(10px); /* Frosted Glass Effect */
    background: rgba(255, 255, 255, 0.1); /* Semi-transparent */
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    border-radius: 15px;
}

/* Containers */
.container {
    background: rgba(255, 255, 255, 0.3); /* Transparent Card */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    max-width: 800px;
}

/* Welcome Banner */
.welcome-container {
    background: rgba(0, 87, 255, 0.8);
    color: white;
    padding: 20px;
    text-align: center;
    width: 80%;
    margin-top: 20px;
    border-radius: 10px;
    font-size: 20px;
}

/* Load Cards */
.load-container {
    width: 80%;
    margin-top: 20px;
    padding: 20px;
    border-radius: 10px;
    backdrop-filter: blur(10px); /* Frosted Glass */
    background: rgba(255, 255, 255, 0.3);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.load-card {
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.4);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.load-card button {
    background: #0057ff;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

.load-card button:hover {
    background: #003fcc;
    transform: scale(1.1);
}

/* Profile Section */
.profile-container {
    max-width: 400px;
    margin: auto;
    padding: 20px;
    background: rgba(255, 255, 255, 0.2);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    text-align: center;
}

.profile-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 15px;
}

/* Buttons */
button {
    background: linear-gradient(to right, #0a0f3b, #0057ff);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: 0.3s;
}

button:hover {
    background: #003fcc;
    transform: scale(1.05);
}


    </style>
</head>
<body>
    <div class="sidebar">
        <h2> Route Rover </h2>
        <ul>
        <a href="#" onclick="showHome()">Home</a>
        <a href="postload.php">Post a Load</a>
        <a href="noti.php" onclick="">Notification</a>
        <a href="#"onclick="showInsuranceInfo()">Insurance</a>
        <a href="#" onclick="showRouteRoverInfo()">Meet Route Rover</a>
        <a href="#" onclick="showProfile(); return false;">View Profile</a>
          <a href="loginshipper.php">Log Out</a>
        </ul>
    </div>
    
    
    <div class="main-content" id="mainContent">
        <div class="container" id="homeContent">
            <h1>Welcome to Route Rover</h1>
            <p>📦📤 Shipper, please post your load and enjoy our services.</p>
        </div>
    <script>
    function toggleDropdown() {
        var dropdown = document.getElementById("profileDropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }
    function showHome() {
    // Clear existing content
    document.getElementById("mainContent").innerHTML = `
        <div class="container">
            <h1>Welcome to Route Rover</h1>
            <p>📦📤 Shipper, please post your load and enjoy our services.</p>
        </div>
    `;

    // Fetch the posted loads for the logged-in user
    fetch("fetch_loads1.php")
        .then(response => response.json())
        .then(data => {
            if (!Array.isArray(data) || data.length === 0) {
                // If no loads found, display a message
                document.getElementById("mainContent").innerHTML += `
                    <div class="container">
                        <p>No loads posted yet.</p>
                    </div>
                `;
            } else {
                // Display the posted loads
                let loadContent = '';
                data.forEach(load => {
                    loadContent += `
                        <div class="load-card">
                            <div>
                                <p><strong>Pickup:</strong> ${load.pickup}</p>
                                <p><strong>Drop:</strong> ${load.drop}</p>
                                <p><strong>Material:</strong> ${load.material}</p>
                                <p><strong>Quantity:</strong> ${load.quantity} Tonnes</p>
                                <p><strong>Vehicle Type:</strong> ${load.vehicletype}</p>
                                <p><strong>Truck Body:</strong> ${load.truckbody}</p>
                                <p><strong>Tyres:</strong> ${load.tyre}</p>
                                <p><strong>Payment Method:</strong> ${load.paymentmethod}</p>
                            </div>
                            <button onclick="viewLoadDetails('${load.pickup}', '${load.destination}', '${load.material}', '${load.quantity}', '${load.vehicle_type}', '${load.truck_body}', '${load.tyre}', '${load.payment_method}')">
                                View Details
                            </button>
                        </div>
                    `;
                });

                document.getElementById("mainContent").innerHTML += `
                    <div class="load-container">
                        <h2>Your Loads</h2>
                        ${loadContent}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error("Error fetching loads:", error);
        });
}

// Function to handle "View Details" button click
function viewLoadDetails(pickup, drop, material, quantity, vehicletype, truckbody, tyre, paymentmethod) {
    alert(`
        📍 Pickup: ${pickup}
        📍 Drop: ${drop}
        📦 Material: ${material}
        ⚖️ Quantity: ${quantity} Tonnes
        🚚 Vehicle Type: ${vehicletype}
        🚛 Truck Body: ${truckbody}
        🔘 Tyres: ${tyre}
        💰 Payment Method: ${paymentmethod}
    `);
}



    function showLoadForm() {
        document.getElementById("mainContent").innerHTML = `
            <div class="container form-container">
                <h2>Post a Load</h2>
                <input type="hidden" id="s_id" value="1">
                <label>Pickup Location:</label>
                <input type="text" id="pickup">
                <label>Drop Location:</label>
                <input type="text" id="drop">
                <label>Material:</label>
                <select id="material">
                    <option>Wood</option>
                    <option>Steel</option>
                    <option>Medical</option>
                    <option>SME Tools</option>
                </select>
                <label>Quantity:</label>
                <input type="number" id="quantity">
                <label>Vehicle Type:</label>
                <select id="vehicleType">
                    <option>Mini Truck</option>
                    <option>Tanker</option>
                    <option>Trailer</option>
                </select>
                <label>Vehicle Body Type:</label>
                <select id="vehicleBody">
                    <option>Open</option>
                    <option>Close</option>
                </select>
                <label>Tyre Count:</label>
                <input type="number" id="tyre">
                <label>Payment Method:</label>
                <select id="paymentMethod">
                    <option>Online</option>
                    <option>Cash</option>
                </select>
                <div class="buttons">
                    <button onclick="clearForm()">Clear All</button>
                    <button onclick="postLoad()">Post Load</button>
                </div>
            </div>`;
    }
    
    function showRouteRoverInfo() {
            document.getElementById("mainContent").innerHTML = `
                <div class="container route-info">
                    <img src="logo-removebg-preview (1).png" alt="Route Rover Logo">
                    <h2>Route Rover</h2>
                    <p>Route Rover – A versatile navigation tool for exploring and planning routes, weather for transportation, 
                    outdoor activities, or automated systems.</p>
                    <p>Route Rover helps transport materials from one place to another. It is a platform where truck owners and 
                    truck logistics providers can expand their business and grow.</p>
                    <p>Route Rover also provides **insurance** for truck owners, ensuring their safety and security. Additionally, 
                    it offers cargo insurance for **comprehensive protection**, helping businesses plan for a secure future.</p>
                </div>`;
        }
        function showInsuranceInfo() {
            document.getElementById("mainContent").innerHTML = `
                <div class="container route-info">
                    <img src="sorry.png" alt="sorry">
                    <h2>Insurance service</h2>
                    <p>we are very sorry</p>
                    <p>This service is not aavailable yet!!</p>
                    <p>it will coming soon.</p>
                </div>`;
        }
        function loadDirectory() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_transportation_data.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("mainContent").innerHTML = `
                    <div class="container">
                        <h2>Transportation Directory</h2>
                        <div id="directoryData">${xhr.responseText}</div>
                    </div>
                `;
            }
        };
        xhr.send();
    } 
    function clearForm() {
        document.querySelectorAll(".form-container input, .form-container select").forEach(field => field.value = "");
    }

    function postLoad() {
    const postButton = document.querySelector(".buttons button:last-child"); // Get "Post Load" button
    postButton.disabled = true; // Disable button to prevent multiple clicks
    postButton.textContent = "Posting..."; // Change button text to indicate processing

    const formData = new FormData();
    formData.append("s_id", document.getElementById("s_id").value);
    formData.append("pickup", document.getElementById("pickup").value);
    formData.append("destination", document.getElementById("drop").value);
    formData.append("material", document.getElementById("material").value);
    formData.append("quantity", document.getElementById("quantity").value);
    formData.append("vehicle_type", document.getElementById("vehicleType").value);
    formData.append("truck_body", document.getElementById("vehicleBody").value);
    formData.append("tyre", document.getElementById("tyre").value);
    formData.append("payment_method", document.getElementById("paymentMethod").value);

    fetch("loaddata.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "success") {
            const successPopup = document.getElementById("successPopup");
            successPopup.style.display = "block"; // Show success popup

            setTimeout(() => { 
                successPopup.style.display = "none"; 
                postButton.disabled = false; // Re-enable button after success
                postButton.textContent = "Post Load"; // Reset button text

                const amount = 500; // Adjust amount dynamically if needed
                const paymentMethod = document.getElementById("paymentMethod").value;
                window.location.href = `payment.php?amount=${amount}&payment_method=${encodeURIComponent(paymentMethod)}`;
                showHome(); // Redirect back to home page
            }, 2000);
        } else {
            alert("Error: " + data);
            postButton.disabled = false; // Re-enable button on error
            postButton.textContent = "Post Load";
        }
    })
    .catch(error => {
        console.error("Error:", error);
        postButton.disabled = false; // Re-enable button on error
        postButton.textContent = "Post Load";
    });
}
function showProfile() {
    console.log("🔍 View Profile button clicked!");

    fetch("fetch_profile.php") // Ensure this file exists and is correct
        .then(response => response.json()) // Convert response to JSON
        .then(data => {
            console.log("✅ Parsed JSON Data:", data);

            if (data.error) {
                alert("❌ Error: " + data.error);
                return;
            }

            document.getElementById("mainContent").innerHTML = `
                <div class="profile-container">
                <h2>User Profile</h2>
                    <img class="profile-icon" src="${data.profile_photo || 'default.png'}" alt="Profile Picture">
                    <p><strong>Name:</strong> ${data.name}</p>
                    <p><strong>Email:</strong> ${data.email}</p>
                    <p><strong>Phone:</strong> ${data.phone}</p>
                    <p><strong>FullNmae:</strong> ${data.fullname}</p>
                     <p><strong>Address:</strong> ${data.address}</p>
                     <p><strong>Date Of Birth:</strong> ${data.dob}</p>
                     <p><strong>Age:</strong> ${data.age}</p>
                    <button onclick="editProfile()">Edit Profile</button>
                </div>
            `;
        })
        .catch(error => {
            console.error("⚠️ Error fetching profile:", error);
            alert("Failed to load profile data.");
        });
}

function editProfile() {
    // Redirect to the edit_profile.php page
    window.location.href = "edit_shipper_profile.php";
}



</script>

</body>
</html>