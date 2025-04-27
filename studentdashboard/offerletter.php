<?php
// Include database connection
include 'db.php';
session_start();

// Get the student's email from session
$email = $_SESSION['email']; // Assuming user is logged in and email is stored in session

// Handle Offer Letter Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['offer_letter'])) {
    $offerLetter = $_FILES['offer_letter'];

    // Assuming the offer letter is uploaded to the "uploads" directory
    $uploadDir = '../upload/';
    $uploadFile = $uploadDir . basename($offerLetter['name']);
    
    // Check if the file was uploaded without errors
    if ($offerLetter['error'] == 0) {
        // Check if the file is an actual file and not a fake
        if (is_uploaded_file($offerLetter['tmp_name'])) {
            if (move_uploaded_file($offerLetter['tmp_name'], $uploadFile)) {
                // Offer letter uploaded successfully, update the confirm_status in database
                $updateQuery = "UPDATE student_info SET confirm_status = 'Offer Letter Uploaded', offer_letter = '$uploadFile' WHERE email = '$email'";
                if ($conn->query($updateQuery)) {
                    // Set the session status to show offer letter uploaded
                    $_SESSION['confirm_status'] = 'Offer Letter Uploaded';
                    echo "<script>alert('Offer letter uploaded successfully!'); window.location.href = 'student_dashboard.php';</script>";
                } else {
                    echo "Error updating status.";
                }
            } else {
                echo "Error moving uploaded file.";
            }
        } else {
            echo "Uploaded file is not valid.";
        }
    } else {
        echo "Error uploading file: " . $offerLetter['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Offer Letter</title>
</head>
<body>
    <h2>Upload Offer Letter</h2>
    <form action="offer_letter.php" method="POST" enctype="multipart/form-data">
        <label for="offer_letter">Select Offer Letter:</label>
        <input type="file" name="offer_letter" id="offer_letter" required><br><br>
        <button type="submit">Upload</button>
    </form>
    <br>
    <a href="http://localhost:8008/placement management system/student_dashboard.php">Back to Dashboard</a>
</body>
</html>
