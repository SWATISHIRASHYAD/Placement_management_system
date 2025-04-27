<?php
session_start();
session_destroy(); // Destroy the session

// Show a logout message
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional: Link your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        .message {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 5px;
        }
        .redirect {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="message">
        <h2>You have successfully logged out!</h2>
        <p>You will be redirected to the login page shortly.</p>
    </div>
    <div class="redirect">
        <p>If you are not redirected, <a href="http://localhost:8008/placement management system/login/logintej.html">click here</a> to go to the login page.</p>
    </div>
    
    <script>
        // Redirect after 3 seconds
        setTimeout(function() {
            window.location.href = "http://localhost:8008/placement management system/login/logintej.html"; // Change to your actual login page
        }, 3000);
    </script>
</body>
</html>