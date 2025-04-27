<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registerplacement";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Display the allotment results
$sql = "SELECT student_info.usn, rooms.room_name 
        FROM student_info 
        LEFT JOIN rooms ON student_info.room_id = rooms.room_id
        ORDER BY rooms.room_name ASC, student_info.usn ASC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Room Allotment Details:</h2>";
    echo "<table border='1'>
            <tr>
                <th>Student USN</th>
                <th>Room Name</th>
            </tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['usn'] . "</td>
                <td>" . ($row['room_name'] ? $row['room_name'] : "Not Allotted") . "</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "No students found.";
}

$conn->close();
?>