<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registerplacement";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$usn = $_POST['usn'];
$fname = $_POST['fname'];
$mname = $_POST['mname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$branch = $_POST['branch'];
$dob = $_POST['dob'];
$cgpa = $_POST['cgpa'];
$contact = $_POST['contact'];

// Handle resume upload
$upload_dir = "uploads/resumes/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true); // Create folder if it doesn't exist
}

$resume_file = $_FILES['resume'];
$resume_name = basename($resume_file['name']);
$resume_tmp = $resume_file['tmp_name'];
$resume_size = $resume_file['size'];
$resume_ext = strtolower(pathinfo($resume_name, PATHINFO_EXTENSION));
$allowed_ext = ['pdf', 'doc', 'docx', 'png']; // Allowed file types
$resume_path = $upload_dir . uniqid('resume_') . '.' . $resume_ext; // Unique file path

// File validation
if (!in_array($resume_ext, $allowed_ext)) {
    die("Invalid file type. Only PDF, DOC, DOCX, and PNG are allowed.");
}

if ($resume_size > 5 * 1024 * 1024) { // Limit: 5MB
    die("File size exceeds the limit of 5MB.");
}

// Check for duplicate entry (USN or Email)
$check_sql = "SELECT * FROM student_info WHERE usn = ? OR email = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ss", $usn, $email);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo "<p style='color: red; font-weight: bold; font-size:50px; text-align:center;'>Error: Duplicate entry detected. USN or Email already exists.Update in Profile Section</p>";
} else {
    // Proceed to upload resume and insert data
    if (move_uploaded_file($resume_tmp, $resume_path)) {
        // Insert data into the database
        $sql = "INSERT INTO student_info (usn, first_name, middle_name, last_name, email, branch, dob, cgpa, contact_no, resume_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $usn, $fname, $mname, $lname, $email, $branch, $dob, $cgpa, $contact, $resume_path);

        if ($stmt->execute()) {
            echo "<p style='color: green; font-weight: bold;'>Registration successful!</p>";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "<p style='color: red; font-weight: bold;'>Failed to upload resume. Please try again.</p>";
    }
}

// Close the connection
$conn->close();
?>
