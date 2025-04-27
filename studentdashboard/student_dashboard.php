<?php
// Include database connection
include 'db.php';
session_start();
// Get the student's email from session
$email = $_SESSION['email']; // Assuming user is logged in and email is stored in session

// Query to check if the student is placed and has uploaded the offer letter
$query = "SELECT confirm_status FROM student_info WHERE email = '$email'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $confirmStatus = $row['confirm_status'];
} else {
    $confirmStatus = null; // In case no record is found
}

// Handle Offer Letter Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['offer_letter'])) {
    $offerLetter = $_FILES['offer_letter'];
    
    // Assuming the offer letter is uploaded to the "uploads" directory
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($offerLetter['name']);
    
    if (move_uploaded_file($offerLetter['tmp_name'], $uploadFile)) {
        // Offer letter uploaded successfully, update the confirm_status
        $updateQuery = "UPDATE student_info SET confirm_status = 'Offer Letter Uploaded' WHERE email = '$email'";
        if ($conn->query($updateQuery)) {
            $confirmStatus = 'Offer Letter Uploaded'; // Update the status
        } else {
            echo "Error updating status.";
        }
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        /* General Layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f4f4f9;
        }

        .container {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 20%;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #ecf0f1;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active-link {
            background-color: #3498db;
            color: #fff;
            font-weight: bold;
        }

        /* Main Content */
        .content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            animation: fadeIn 0.5s ease-in-out;
            background-image: url('http://localhost:8008/placement management system/studentimage.jpg'); /* Replace with the actual image path */
            background-size: cover; /* Ensure the image covers the entire area */
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Prevent the image from repeating */
            color: black; /* White text for better contrast against background image */
        }

        /* Sections */
        .section {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .section.active {
            display: block;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Pop-up Modal Styles */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .popup-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
        }

        .popup button {
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup button:hover {
            background-color: #2980b9;
        }

    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Student Dashboard</h2>
            <ul>
                <li><a href="http://localhost:8008/placement management system/navigationbar.html">Home</a></li>
                <li><a href="http://localhost:8008/placement management system/registerform.html">Add Credentials</a></li>
                <li><a href="#profile" onclick="showSection('profile')">My Profile</a></li>
                <li><a href="#registration" onclick="showSection('registration')">Active Companies</a></li>
                <li><a href="#announcements" onclick="showSection('announcements')">Circular</a></li>
                <li><a href="#register" onclick="showSection('register')">Applied Companies</a></li>
                <li><a href="http://localhost:8008/placement management system/hod_dashboard/logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- Main content area -->
        <div class="content">
            <div id="profile" class="section">
                <?php include 'profile_section.php'; ?>
            </div>
            <div id="registration" class="section">
                <?php include 'registerforcompany.php'; ?>
            </div>
            <div id="announcements" class="section">
                <?php include 'announcement_section.php'; ?>
            </div>
            <div id="register" class="section">
                <?php include 'viewregistration.php'; ?>
            </div>
            <div id="company-info" class="section">
                <h2>Company Information</h2>
                <!-- Add company info content -->
            </div>
        </div>
    </div>

    <!-- Pop-up Modal for Placed Students -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <h2>Congratulations on Your Placement!</h2>
            <p>Please upload your offer letter.</p>
            <form action="offerletter.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="offer_letter" required><br><br>
                <button type="submit">Upload Offer Letter</button>
            </form>
            <button onclick="closePopup()">Close</button>
        </div>
    </div>

    <script>
        // Function to show the selected section and hide others
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active'); // Remove 'active' class
            });

            // Highlight the selected link in the sidebar
            document.querySelectorAll('.sidebar a').forEach(link => {
                link.classList.remove('active-link'); // Remove active-link class
            });

            // Show the selected section
            const selectedSection = document.getElementById(sectionId);
            if (selectedSection) {
                selectedSection.classList.add('active');

                // Highlight the corresponding link in the sidebar
                const activeLink = document.querySelector(`.sidebar a[href="#${sectionId}"]`);
                if (activeLink) {
                    activeLink.classList.add('active-link');
                }
            } else {
                alert("Section not found!"); // Error message if section ID is invalid
            }
        }

        // Function to display the pop-up if the student is placed and hasn't uploaded offer letter
        <?php if ($confirmStatus == 'Confirmed' && $confirmStatus !== 'Offer Letter Uploaded') { ?>
            setTimeout(function() {
                document.getElementById('popup').style.display = 'flex';
            }, 2000); // Popup after 2 seconds
        <?php } ?>

        // Function to close the pop-up
        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>
</body>
</html>
