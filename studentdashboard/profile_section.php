<?php


// Display all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registerplacement"; // Single database for both tables

// Create a single connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the session has an email stored
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
} else {
    echo "No user is logged in!";
    exit();
}

// Fetch student profile information from the 'student_info' table
$sql = "SELECT * FROM student_info WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$student_info = $result->fetch_assoc();

// Check if profile information was retrieved
if (!$student_info) {
    echo "No profile information found for the provided email.";
    exit();
}

// Check if form is submitted to update information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usn = $_POST['usn'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $branch = $_POST['branch'];
    $dob = $_POST['dob'];
    $cgpa = $_POST['cgpa'];
    $contact_no = $_POST['contact_no'];


    // Update query to modify student profile information
    $update_sql = "UPDATE student_info SET usn=?, first_name=?, middle_name=?, last_name=?, branch=?, dob=?, cgpa=?, contact_no=?,resume_path=? WHERE email=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssssss", $usn, $first_name, $middle_name, $last_name, $branch, $dob, $cgpa, $contact_no, $email,$resume_path);
    $update_stmt->execute();

    // Refresh the page to show updated information
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Information</title>
    <style>
        a.resume {
    display: inline-block;
    background-color: #28a745; /* Attractive green color */
    color: white; 
    padding: 10px 20px;
    text-decoration: none; /* Removes underline */
    border-radius: 8px; /* Rounded corners */
    font-weight: bold;
    font-family: Arial, sans-serif;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Adds shadow for 3D effect */
    transition: background-color 0.3s, transform 0.2s; /* Smooth hover effects */
}

a.resume:hover {
    background-color: #218838; /* Slightly darker green on hover */
    transform: translateY(-2px); /* Button lift effect */
}

    </style>
</head>
<body>

<div style="max-width: 400px; margin: auto; padding: 20px; background-color: #f8f9fa; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">
    <h2 style="text-align: center; color: #333;">Profile Information</h2>

    <?php if ($student_info): ?>
        <?php if (!isset($_GET['edit'])): ?>
            <!-- Display profile information -->
            <div style="margin-bottom: 10px;"><strong>USN:</strong><p style="color: #555;"><?php echo htmlspecialchars($student_info['usn']); ?></p></div>
            <div style="margin-bottom: 10px;"><strong>First Name:</strong><p style="color: #555;"><?php echo htmlspecialchars($student_info['first_name']); ?></p></div>
            <div style="margin-bottom: 10px;"><strong>Middle Name:</strong><p style="color: #555;"><?php echo htmlspecialchars($student_info['middle_name']); ?></p></div>
            <div style="margin-bottom: 10px;"><strong>Last Name:</strong><p style="color: #555;"><?php echo htmlspecialchars($student_info['last_name']); ?></p></div>
            <div style="margin-bottom: 10px;"><strong>Branch:</strong><p style="color: #555;"><?php echo htmlspecialchars($student_info['branch']); ?></p></div>
            <div style="margin-bottom: 10px;"><strong>DOB:</strong><p style="color: #555;"><?php echo htmlspecialchars($student_info['dob']); ?></p></div>
            <div style="margin-bottom: 10px;"><strong>CGPA:</strong><p style="color: #555;"><?php echo htmlspecialchars($student_info['cgpa']); ?></p></div>
            <div style="margin-bottom: 10px;"><strong>Contact:</strong><p style="color: #555;"><?php echo htmlspecialchars($student_info['contact_no']); ?></p></div>
            <div style="margin-bottom: 10px;">
</div>

            <a href="?edit=true" style="display: inline-block; padding: 8px 16px; background-color: #007bff; color: #fff; text-align: center; border-radius: 4px; text-decoration: none;">Edit Profile</a>
        <?php else: ?>
            <!-- Display form for editing profile information -->
            <form method="post" action="">
                <div style="margin-bottom: 10px;"><label>USN:</label><input type="text" name="usn" value="<?php echo htmlspecialchars($student_info['usn']); ?>" style="width: 100%; padding: 8px;"></div>
                <div style="margin-bottom: 10px;"><label>First Name:</label><input type="text" name="first_name" value="<?php echo htmlspecialchars($student_info['first_name']); ?>" style="width: 100%; padding: 8px;"></div>
                <div style="margin-bottom: 10px;"><label>Middle Name:</label><input type="text" name="middle_name" value="<?php echo htmlspecialchars($student_info['middle_name']); ?>" style="width: 100%; padding: 8px;"></div>
                <div style="margin-bottom: 10px;"><label>Last Name:</label><input type="text" name="last_name" value="<?php echo htmlspecialchars($student_info['last_name']); ?>" style="width: 100%; padding: 8px;"></div>
                <div style="margin-bottom: 10px;"><label>Branch:</label><input type="text" name="branch" value="<?php echo htmlspecialchars($student_info['branch']); ?>" style="width: 100%; padding: 8px;"></div>
                <div style="margin-bottom: 10px;"><label>DOB:</label><input type="date" name="dob" value="<?php echo htmlspecialchars($student_info['dob']); ?>" style="width: 100%; padding: 8px;"></div>
                <div style="margin-bottom: 10px;"><label>CGPA:</label><input type="text" name="cgpa" value="<?php echo htmlspecialchars($student_info['cgpa']); ?>" style="width: 100%; padding: 8px;"></div>
                <div style="margin-bottom: 10px;"><label>Contact:</label><input type="text" name="contact_no" value="<?php echo htmlspecialchars($student_info['contact_no']); ?>" style="width: 100%; padding: 8px;"></div>
                <button type="submit" style="padding: 8px 16px; background-color: #28a745; color: #fff; border: none; border-radius: 4px;">Save Changes</button>
                <a href="?" style="display: inline-block; padding: 8px 16px; background-color: #dc3545; color: #fff; text-decoration: none; border-radius: 4px;">Cancel</a>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <p style="text-align: center; color: #888;">No registration information found.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
