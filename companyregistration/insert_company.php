<?php
// Database connection
$conn = new PDO("mysql:host=localhost;dbname=registerplacement", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $company_name = $_POST['company_name'];
    $description = $_POST['description'];
    $cgpa_cutoff=$_POST['cgpa_cutoff'];

    // Insert company into the database with default inactive status
    $sql = "INSERT INTO companies (company_name, description, is_activated,cgpa_cutoff) VALUES (?, ?, 0,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$company_name, $description,$cgpa_cutoff]);

    // Redirect to the admin dashboard
    header("Location: http://localhost:8008/placement management system/admin dashboard/admin_dashboard.php");
    exit();
}
?>
