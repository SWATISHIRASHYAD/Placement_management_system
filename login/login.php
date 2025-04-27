<?php
include('db.php');
session_start();  // Start the session at the beginning

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL statement
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters and execute statement
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user with the provided email exists
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify the password using password_verify
            if (password_verify($password, $user['password'])) {
                // Store user data in session
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on the role
                if ($user['role'] == 'student') {
                    header("Location: http://localhost:8008/placement management system/studentdashboard/student_dashboard.php");
                    exit();
                } elseif ($user['role'] == 'hod') {
                    header("Location: http://localhost:8008/placement management system/hod_dashboard/hod_dashboard.php");
                    exit();
                } elseif ($user['role'] == 'placement') {
                    header("Location: http://localhost:8008/placement management system/admin dashboard/admin_dashboard.php");
                    exit();
                }
            } else {
                echo "<div style='color: red; font-weight: bold; font-size: 50px; text-align:center;'>
                        Invalid password!
                      </div>";
            }
            
        } else {
            echo "No user found with that email!";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Database query failed: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
