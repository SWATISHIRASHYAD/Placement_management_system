<?php
// Database connection
$conn = new PDO("mysql:host=localhost;dbname=registerplacement", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get the company_id from the URL
if (isset($_GET['company_id'])) {
    $company_id = $_GET['company_id'];

    // Fetch the current activation status
    $sql = "SELECT is_activated FROM companies WHERE company_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$company_id]);
    $company = $stmt->fetch();

    if ($company) {
        if (isset($_GET['action']) && $_GET['action'] === 'deactivate') {
            // Deactivate company and delete registrations
            $conn->beginTransaction();
            try {
                // Delete registrations
                $sql = "DELETE FROM student_registrations WHERE company_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$company_id]);

                // Deactivate the company
                $sql = "UPDATE companies SET is_activated = 0 WHERE company_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$company_id]);

                $conn->commit();
                // Redirect to admin dashboard
                header("Location: http://localhost:8008/placement management system/companyregisteration/admindashboard.php");
                exit();
            } catch (Exception $e) {
                $conn->rollBack();
                echo "Error during deactivation: " . $e->getMessage();
                exit();
            }
        } else {
            if ($company['is_activated'] == 1) {
                // Confirm deactivation via JavaScript
                echo '<script>
                    if (confirm("Are you sure you want to deactivate this company? This will delete all registrations for this company.")) {
                        window.location.href = "?company_id=' . $company_id . '&action=deactivate";
                    } else {
                        window.location.href = "admindashboard.php";
                    }
                </script>';
            } else {
                // Activate the company
                $sql = "UPDATE companies SET is_activated = 1 WHERE company_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$company_id]);

                // Redirect to admin dashboard
                header("Location: http://localhost:8008/placement management system/companyregisteration/admindashboard.php");
                exit();
            }
        }
    } else {
        echo "Company not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>
