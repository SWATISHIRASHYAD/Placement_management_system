<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: grey;
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar for Navigation -->
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="http://localhost:8008/placement management system/navigationbar.html">Home</a></li>
                <li><a href="http://localhost:8008/placement management system/show.php">Student Details</a></li>
                <li><a href="http://localhost:8008/placement management system/admin dashboard/announcement_show.php">Circular</a></li>
                <li><a href="http://localhost:8008/placement management system/admin dashboard/registrationlist.php">Registered Student list</a></li>
                <li><a href="http://localhost:8008/placement management system/hod_dashboard/logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- Main Content Area -->
        <div class="content">
            <div id="student_details" class="section">
                <h2>Student Details</h2>
                <?php include 'profile_section.php'; ?>
            </div>
            
            <div id="announce" class="section" style="display:none;">
                <h2>Announce</h2>
                <?php include 'announcement_section.php'; ?>
            </div>
            <div id="announcements" class="section" style="display:none;">
                <h2>Circular</h2>
                <?php include 'announcement_show.php'; ?>
            </div>
            <div id="company-info" class="section" style="display:none;">
                <h2>Company Information</h2>
                <?php include 'company_info.php'; ?>
            </div>
        </div>

    </div>

    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
        }
    </script>
</body>
</html>
