<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: logintruck.php");
    exit();
}
include 'db_connect.php'; // Include your database connection file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truck Owner Page</title>
    <style>
        body {
    display: flex;
    margin: 0;
    font-family: Arial, sans-serif;
    background: url('dashbg.jpg') no-repeat center center fixed;
    background-size: cover;
}

.sidebar {
    width: 250px;
    height: 100vh;
    background: rgba(105, 107, 109, 0.8); /* Translucent sidebar */
    color: white;
    padding-top: 20px;
    position: fixed;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 20px;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    padding: 15px;
    text-align: center;
    cursor: pointer;
    transition: 0.3s;
}

.sidebar ul li:hover {
    background: rgba(52, 73, 94, 0.8);
}

.content {
    margin-left: 250px;
    padding: 20px;
    width: calc(100% - 250px);
}

.profile-container, .edit-container {
    max-width: 400px;
    margin: auto;
    padding: 20px;
    background: rgba(255, 255, 255, 0.7); /* Translucent effect */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

.profile-info {
    font-size: 16px;
    margin: 10px 0;
    color: #333;
}

.edit-btn {
    background: rgba(44, 62, 80, 0.9);
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 15px;
    font-size: 14px;
    transition: 0.3s;
}

.edit-btn:hover {
    background: rgba(52, 73, 94, 0.9);
}

input, textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Route Rover</h2>
        <ul>
            <li onclick="showContent('home')">Home</li>
            <li onclick="window.location.href='upload_vehicle.php'">Upload Vehicle Details</li>
            <li onclick="showContent('find-loads')">Find Loads</li>
            <li onclick="showContent('load-directory')">Load Directory</li>
            <li onclick="showContent('vehicle')">My vehicle details</li>
            <li onclick="showContent('profile')">My Profile</li>
            <li onclick="window.location.href='edit_profile.php'">Edit Profile</li>
            <li onclick="showContent('logout')">Log Out</li>
        </ul>
    </div>
    <div class="content" id="main-content">
        <h1>Welcome, Transporter!</h1>
        <p>Select an option from the menu.</p>
    </div>

    <script>
    const locations = [
        "New Delhi", "Mumbai", "Bangalore", "Chennai", "Kolkata", "Hyderabad", "Ahmedabad", "Gandhinagar", "Himatnagar", "Pune",
        "Jaipur", "Lucknow", "Kanpur", "Nagpur", "Indore", "Vadodara", "Surat", "Patna", "Bhopal",
        "Chandigarh", "Coimbatore", "Visakhapatnam", "Madurai", "Agra", "Nashik", "Faridabad",
        "Meerut", "Noida", "Jammu", "Ludhiana", "Ranchi", "Raipur", "Rajasthan", "Gujarat", "Guwahati", "Jabalpur", "Vijayawada"
    ];

    function showSuggestions(inputElement) {
        let parent = inputElement.parentElement;
        let suggestionsContainer = parent.querySelector('.autocomplete-container');
        suggestionsContainer.innerHTML = ''; 
        suggestionsContainer.style.display = 'none';

        let input = inputElement.value.trim();
        if (!input) return;

        const filteredLocations = locations.filter(location =>
            location.toLowerCase().includes(input.toLowerCase())
        );

        filteredLocations.forEach(location => {
            const suggestionItem = document.createElement('div');
            suggestionItem.classList.add('autocomplete-suggestion');
            suggestionItem.textContent = location;
            suggestionItem.onclick = () => {
                inputElement.value = location;
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.style.display = 'none';
            };
            suggestionsContainer.appendChild(suggestionItem);
        });

        if (filteredLocations.length > 0) {
            suggestionsContainer.style.display = 'block';
        }
    }

    document.addEventListener('click', function(event) {
        document.querySelectorAll('.autocomplete-container').forEach(container => {
            if (!container.parentElement.contains(event.target)) {
                container.style.display = 'none';
            }
        });
    });

    function searchLoads() {
    const pickupInput = document.getElementById("pickup");
    const destinationInput = document.getElementById("destination");
    const pickup = pickupInput.value.trim();
    const destination = destinationInput.value.trim();
    const resultsContainer = document.getElementById("search-results");

    // Reset results
    resultsContainer.innerHTML = "";
    
    // Input validation
    if (!pickup || !destination) {
        alert("Please enter both Pickup and Destination.");
        return;
    }

    // Fetch loads from the server
    fetch(`search_loads.php?pickup=${encodeURIComponent(pickup)}&destination=${encodeURIComponent(destination)}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success" && data.loads.length > 0) {
                let loadCards = data.loads.map(load => `
                    <div class="load-card" style="border: 1px solid #ddd; padding: 15px; width: 300px; border-radius: 10px; background: white;">
                        <h3>Pickup: ${load.pickup}</h3>
                        <p><strong>Destination:</strong> ${load.drop}</p>
                        <p><strong>Material:</strong> ${load.material}</p>
                        <p><strong>Quantity:</strong> ${load.quantity} tons</p>
                        <p><strong>Vehicle Type:</strong> ${load.vehicletype}</p>
                        <p><strong>Truck Body:</strong> ${load.truckbody}</p>
                        <p><strong>Tyres:</strong> ${load.tyre}</p>
                        <p><strong>Payment Method:</strong> ${load.paymentmethod}</p>
                        <button onclick="bookNow('${load.pickup}', '${load.destination}', '${load.material}')" 
                            style="padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; width: 100%;">Book Now</button>
                    </div>
                `).join('');
                resultsContainer.innerHTML = `<div style="display: flex; flex-wrap: wrap; gap: 10px;">${loadCards}</div>`;
            } else {
                resultsContainer.innerHTML = '<p>No loads found for the given route.</p>';
            }
        })
        .catch(error => {
            console.error("Error fetching loads:", error);
            resultsContainer.innerHTML = '<p>Error fetching loads. Please try again later.</p>';
        });
}
function bookNow(loadId, shipperId) {
    console.log("✅ Book Now Clicked!");
    console.log("📦 Load ID:", loadId);
    console.log("🚛 Shipper ID:", shipperId);

    if (!loadId || !shipperId) {
        console.error("❌ Error: Load ID or Shipper ID is missing!");
        return;
    }

    fetch("notification.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ 
            load_id: loadId,  
            s_id: shipperId 
        }),
    })
    .then(response => response.json())
    .then(data => {
        console.log("📩 Server Response:", data);
        if (data.success) {
            alert("✅ Booking request sent! Notification added.");
        } else {
            alert("❌ Error: " + data.message);
        }
    })
    .catch(error => console.error("❌ Fetch Error:", error));
}


 function showContent(page) {
        const content = document.getElementById('main-content');

        if (page === 'home') {
            content.innerHTML = '<h1>Welcome, Transporter!</h1><p>This is your dashboard.</p>';
        } else if (page === 'vehicle') {
    fetch("fetch_vehicle.php")
        .then(response => response.json()) // Parse JSON response
        .then(data => {
            let content = document.getElementById("main-content");

            // If error, show message and stop execution
            if (data.status === "error") {
                content.innerHTML = `<h1>My Vehicle Details</h1><p>${data.message}</p>`;
                return;
            }

            // Create the table structure
            let tableHTML = `
                <h1>My Vehicle Details</h1>
                <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr>
                            <th>Vehicle Register No</th>
                            <th>Vehicle Type</th>
                            <th>Vehicle Tyre</th>
                            <th>Route Permission</th>
                        </tr>
                    </thead>
                    <tbody id="vehicleDetails">
                    </tbody>
                </table>
            `;
            content.innerHTML = tableHTML;

            let tableBody = document.getElementById("vehicleDetails");

            // Loop through vehicle data and create table rows
            data.vehicles.forEach(vehicle => {
                let row = `<tr>
                    <td>${vehicle.vehicle_register_no}</td>
                    <td>${vehicle.vehicle_type}</td>
                    <td>${vehicle.vehicle_tyre}</td>
                    <td>${vehicle.route_permission}</td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => {
            console.error("Error fetching vehicle details:", error);
            document.getElementById("main-content").innerHTML = `<h1>My Vehicle Details</h1><p>Error fetching vehicle details.</p>`;
        });
}

 else if (page === 'find-loads') {
            content.innerHTML = `
                <h1>Find Loads</h1>
                <div style="margin-bottom: 15px;">
                    <input type="text" id="pickup" placeholder="Enter Pickup Location" oninput="showSuggestions(this)"
                        style="padding: 10px; width: 200px; border: 1px solid #ccc; border-radius: 5px;">
                    <div class="autocomplete-container" style="position: absolute; background: white; border: 1px solid #ddd; width: 200px; max-height: 150px; overflow-y: auto;"></div>

                    <input type="text" id="destination" placeholder="Enter Destination Location" oninput="showSuggestions(this)"
                        style="padding: 10px; width: 200px; border: 1px solid #ccc; border-radius: 5px;">
                    <div class="autocomplete-container" style="position: absolute; background: white; border: 1px solid #ddd; width: 200px; max-height: 150px; overflow-y: auto;"></div>

                    <button onclick="searchLoads()" style="padding: 10px 20px; background: #2c3e50; color: white; 
                        border: none; border-radius: 5px; cursor: pointer;">Search</button>
                </div>
                <div id="search-results"></div>
            `;
        } else if (page === 'load-directory') {
    fetch('fetch_loads.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                let loads = data.data;
                let cards = loads.map(load => `
                    <div class="load-card" style="border: 1px solid #ddd; padding: 15px; width: 300px; border-radius: 10px; background: white;">
                        <h3>Pickup: ${load.pickup}</h3>
                        <p><strong>Destination:</strong> ${load.drop}</p>
                        <p><strong>Material:</strong> ${load.material}</p>
                        <p><strong>Quantity:</strong> ${load.quantity} tons</p>
                        <p><strong>Vehicle Type:</strong> ${load.vehicletype}</p>
                        <p><strong>Truck Body:</strong> ${load.truckbody}</p>
                        <p><strong>Tyres:</strong> ${load.tyre}</p>
                        <p><strong>Payment Method:</strong> ${load.paymentmethod}</p>
                         <button onclick="bookNow(${load.load_id}, ${load.s_id})"
                    style="padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; width: 100%;">Book Now</button>
                </div>
                `).join('');

                content.innerHTML = `
                    <h1>Load Directory</h1>
                    <div class="load-container" style="display: flex; flex-wrap: wrap; gap: 10px;">${cards}</div>
                `;
            } else {
                content.innerHTML = '<h1>Load Directory</h1><p>No loads available.</p>';
            }
        })
        .catch(error => console.error("Error fetching loads:", error));
}
else if (page === 'profile') {
            fetch('fetch_tra_profile.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        let profile = data.data;
                        content.innerHTML = `
                            <div class="profile-container">
                                <img class="profile-icon" src="${profile.profile_photo || 'default.png'}" alt="Profile Picture">
                                <h2>${profile.name}</h2>
                                <p class="profile-info"><strong>Email:</strong> ${profile.email}</p>
                                <p class="profile-info"><strong>Phone:</strong> ${profile.phone}</p>
                                 <p class="profile-info"><strong>FullName</strong> ${profile.fullname}</p>
                                <p class="profile-info"><strong>Address:</strong> ${profile.address}</p>
                                <p class="profile-info"><strong>DOB:</strong> ${profile.dob}</p>
                                <p class="profile-info"><strong>Age:</strong> ${profile.age}</p>
                            </div>`;
                    } else {
                        content.innerHTML = '<h1>My Profile</h1><p>Error loading profile.</p>';
                    }
                });
        } else if (page === 'logout') {
            window.location.href = 'logintruck.php';
        }
    }
   
</script>

</body>
</html>
