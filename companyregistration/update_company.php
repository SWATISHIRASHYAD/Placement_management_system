<?php
// Database connection
$conn = new PDO("mysql:host=localhost;dbname=registerplacement", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_id = $_POST['company_id'];
    $company_name = $_POST['company_name'];
    $description = $_POST['description'];
    $is_activated = $_POST['is_activated'];
    $cgpa_cutoff=$_POST['cgpa_cutoff'];
    // Update company information
    $sql = "UPDATE companies SET company_name = ?, description = ?, is_activated = ? ,cgpa_cutoff = ? WHERE company_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$company_name, $description, $is_activated, $cgpa_cutoff, $company_id]);

    // Redirect to the admin dashboard
    header("Location: http://localhost:8008/placement management system/companyregisteration/admindashboard.php");
    exit();
}
?>
