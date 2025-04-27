<?php
// Include database connection
include 'db1.php';

// Fetch announcements
$sql = "SELECT title, content, posted_on FROM announcements ORDER BY posted_on DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link rel="stylesheet" href="announceshow.css"> <!-- Link to your CSS file -->
</head>

<body>

<div class="announcements">
    <h2>Announcements</h2>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='announcement'>";
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<p>" . htmlspecialchars($row['content']) . "</p>";
            echo "<small>Posted on: " . date('Y-m-d H:i', strtotime($row['posted_on'])) . "</small>";
            echo "</div><hr>";
        }
    } else {
        echo "<p>No announcements at the moment.</p>";
    }

    $conn->close();
    ?>
</div>

</body>

</html>
