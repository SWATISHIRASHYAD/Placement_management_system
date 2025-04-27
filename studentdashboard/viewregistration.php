<?php
// Start the session to access the logged-in student's data


// Check if the student is logged in (i.e., session variable 'email' exists)
if (!isset($_SESSION['email'])) {
    echo "You need to log in first!";
    exit;
}

$email = $_SESSION['email']; // Get the logged-in student's email

try {
    // Connect to the database
    $conn = new PDO("mysql:host=localhost;dbname=registerplacement", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch registrations for the logged-in student using email
    $stmt = $conn->prepare("SELECT sr.name, sr.email, sr.cgpa, c.company_name
                            FROM student_registrations sr
                            JOIN companies c ON sr.company_id = c.company_id
                            WHERE sr.email = :email");
    $stmt->execute(['email' => $email]);

    // Check if any rows were returned
    if ($stmt->rowCount() > 0) {
        $registrations = $stmt->fetchAll();
        
        // Display the table and room allocation
        echo "<h2><b>Room Allocation for Your Registration</b></h2>";
        echo "<table class='registration-table'>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Allocated Room</th>
                    </tr>
                </thead>
                <tbody>";

        // Initialize room allocation variables
        $room_count = 0;
        $current_room = 'A';  // Start with Room A

        // Loop through the registrations
        foreach ($registrations as $registration) {
            // Every 40th student, move to the next room
            if ($room_count == 40) {
                $room_count = 0;
                $current_room++; // Move to the next room (A -> B -> C -> ...)
            }

            // Display student registration and room allocation
            echo "<tr>
                    <td>{$registration['name']}</td>
                    <td>{$registration['email']}</td>
                    <td>{$registration['company_name']}</td>
                    <td>Lab {$current_room}</td>
                  </tr>";

            $room_count++; // Increment student count for room allocation
        }

        echo "</tbody></table>";
    } else {
        // No registrations found for the logged-in student
        echo "No registrations found for your account.";
    }

} catch (PDOException $e) {
    // Handle any database connection or query errors
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>

<!-- Add embedded CSS for styling the table -->
<style>
    /* Embedded CSS for table */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        background-color:grey;
    }

    h2 {
        text-align: center;
        margin-top: 20px;
        color: hotpink;
    }

    .registration-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;

    }

    .registration-table th, .registration-table td {
        text-align: center;
        padding: 10px;
        border: 1px solid #ddd;
        
    }

    .registration-table th {
        background-color: #f2f2f2;
        color: #333;
    }

    .registration-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .registration-table tr:hover {
        background-color: #f1f1f1;
    }
</style>
