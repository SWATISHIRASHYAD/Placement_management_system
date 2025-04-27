<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Debug: Check if the usn is received
if (isset($_GET['usn'])) {
    echo "USN received: " . htmlspecialchars($_GET['usn']); // Debug output
}

include 'db.php'; // Include your database connection

// Check if 'usn' is passed via the URL
if (isset($_GET['usn'])) {
    $usn = $_GET['usn'];

    // Fetch student details from the student_info table
    $sql = "SELECT usn, first_name, contact_no FROM student_info WHERE usn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $usn = $student['usn'];
        $name = $student['first_name'];
        $contact = $student['contact_no'];
    } else {
        // If no student found, redirect to student_records.php
        echo "<script>alert('Student not found.'); window.location.href = 'student_records.php';</script>";
        exit;
    }
} else {
    // If 'usn' is not set, redirect to student_records.php
    echo "<script>alert('Invalid request.'); window.location.href = 'student_records.php';</script>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usn = $_POST['usn'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $company_name = $_POST['company_name'];
    $package = $_POST['package'];
    $year = $_POST['year'];

    // Update the confirm_status to 'Confirmed' in student_info table
    $updateSql = "UPDATE student_info SET confirm_status = 'Confirmed' WHERE usn = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("s", $usn);
    if ($stmt->execute()) {
        // Insert placement details into the placement_details table
        $insertSql = "INSERT INTO placement_details (usn, student_name, contact_number, company_name, package, year_of_selection) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ssssdi", $usn, $name, $contact, $company_name, $package, $year);
        if ($stmt->execute()) {
            echo "<script>alert('Placement confirmed and details added successfully!'); window.location.href = 'show.php';</script>";
        } else {
            echo "<script>alert('Failed to add placement details. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Failed to confirm placement. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Placement</title>
</head>
<body>

<h3>Confirm Placement Details for Student</h3>
<form method="POST" action="confirm.php">
    <label for="usn">USN:</label>
    <input type="text" id="usn" name="usn" value="<?php echo htmlspecialchars($usn); ?>" readonly><br>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" readonly><br>
    <label for="contact">Contact Number:</label>
    <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($contact); ?>" readonly><br>
    <label for="company_name">Company Name:</label>
    <input type="text" id="company_name" name="company_name"><br>
    <label for="package">Package (in lakhs):</label>
    <input type="number" id="package" name="package" step="0.01"><br>
    <label for="year">Year of Selection:</label>
    <input type="number" id="year" name="year"><br>
    <input type="submit" value="Confirm Placement">
</form>

</body>
</html>
