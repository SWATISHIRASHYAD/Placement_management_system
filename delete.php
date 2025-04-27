<?php
include 'db.php';

// Check if 'id' is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Get the 'id' from the URL

    // SQL query to delete the record
    $sql = "DELETE FROM student_info WHERE usn = '$id'";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully!";
        header("Location: show.php"); // Redirect back to the main page after successful deletion
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "No ID provided!";
}

$conn->close();
?>
