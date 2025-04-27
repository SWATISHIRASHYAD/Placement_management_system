<?php
// Handle registration logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('db.php');  // Include database connection

    // Check if connection was successful
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Role: student, hod, or placement

    // Encrypt the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert data into users table
    $sql = "INSERT INTO users (email, password, role) VALUES ('$email', '$hashed_password', '$role')";

    // Execute the query and check if the insertion was successful
    if (mysqli_query($conn, $sql)) {
        echo '<div style="color: green; font-size: 50px; font-weight: bold;text-align:center;">Registration successful!</div>';
        // Redirect to the login page after registration
        exit();
    } else {
        // Display error if the query fails
        echo "Error inserting data: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* Import font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap');

        /* Global styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        /* Centering the login form */
        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        /* Heading */
        h2 {
            text-align: center;
            font-weight: 500;
            color: #333;
        }

        /* Form styling */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        input[type="email"],
        input[type="password"],
        select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            outline: none;
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #5C6BC0;
            box-shadow: 0 0 5px rgba(92, 107, 192, 0.5);
        }

        input[type="submit"] {
            background-color: #5C6BC0;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #3f51b5;
        }

        /* Link styling */
        p {
            text-align: center;
            font-size: 14px;
        }

        a {
            text-decoration: none;
            color: #5C6BC0;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-container register-form">
            <h2>Register</h2>
            <form action="" method="POST"> <!-- action="" submits to the same page -->
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                
                <label for="role">Role</label>
                <select name="role" required>
                    <option value="student">Student</option>
                    <option value="hod">HOD</option>
                    <option value="placement">Placement Officer</option>
                </select>
                
                <input type="submit" value="Register">
            </form>
            <p>Already have an account? <a href="http://localhost:8008/placement management system/login/logintej.html">Login here</a></p>
        </div>
    </div>

</body>
</html>
