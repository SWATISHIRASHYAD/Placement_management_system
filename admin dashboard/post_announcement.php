<?php
// Include the database connection
include 'db1.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    // Insert the announcement into the database
    $sql = "INSERT INTO announcements (title, content) VALUES ('$title', '$content')";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_dashboard.php?status=success");
        exit();
    } else {
        echo "<p>Error posting announcement: " . $conn->error . "</p>";
    }
}

$conn->close();
?>
