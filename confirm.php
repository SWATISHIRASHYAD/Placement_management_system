<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registerplacement";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check if 'usn' is passed in the URL
if (isset($_GET['usn'])) {
    $usn = $_GET['usn'];

    // Query to get student details from the student_info table
    $query = "SELECT first_name, contact_no,confirm_status FROM student_info WHERE usn = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $usn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $student_name = $row['first_name'];
        $contact_number = $row['contact_no'];
        $confirm_status= $row['confirm_status'];
    } else {
        echo "No student found with the provided USN.";
        exit();
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usn = $_POST['usn'];
    $student_name = $_POST['student_name'];
    $contact_number = $_POST['contact_number'];
    $company_name = $_POST['company_name'];
    $package = $_POST['package'];
    $year_of_selection = $_POST['year_of_selection'];

    // Prepare the query to insert data into placement_details
    $query = "INSERT INTO placement_details (usn, student_name, contact_number, company_name, package, year_of_selection) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $usn, $student_name, $contact_number, $company_name, $package, $year_of_selection);

    if ($stmt->execute()) {
        $updateSql = "UPDATE student_info SET confirm_status = 'Confirmed' WHERE usn = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("s", $usn);
        $stmt->execute();
        if ($row["confirm_status"] == "Pending") {
            echo "Confirmed";
        }
        echo "<p style='color: green;'>Placement confirmed successfully.</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Placement</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        input[type="text"],
        input[type="submit"],select{
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            outline: none;
            font-size: 1rem;
        }
        input[type="text"]:focus {
            border-color: #007BFF;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: #ffffff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        @media (max-width: 600px) {
            .form-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Confirm Placement for Student</h2>
        <form method="post" action="">
            <input type="hidden" name="usn" value="<?php echo isset($usn) ? htmlspecialchars($usn) : ''; ?>">
            <label for="student_name">Student Name:</label>
            <input type="text" id="student_name" name="student_name" value="<?php echo isset($student_name) ? htmlspecialchars($student_name) : ''; ?>" readonly><br>

            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" value="<?php echo isset($contact_number) ? htmlspecialchars($contact_number) : ''; ?>" readonly><br>

            <label for="company_name">Company Name:</label>
        <select name="company_name">
            <option value="">Select Company</option>
            <option value="Infosys">Infosys</option>
            <option value="Wipro">Wipro</option>
            <option value="Airowire">Airoware</option>
            <option value="Versa Networks">Versa Networks</option>
        </select>

            <label for="package">Package:</label>
            <input type="text" id="package" name="package" required><br>

            <label for="year_of_selection">Year of Selection:</label>
            <input type="text" id="year_of_selection" name="year_of_selection" required><br>

            <input type="submit" value="Confirm">
        </form>
    </div>
</body>
</html>
