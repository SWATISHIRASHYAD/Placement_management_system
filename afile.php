<?php
include 'db.php'; // Include your database connection

// Default filter values
$filter = 'ALL';
$branchFilter = '';
$cgpaFilter = '';

// Get filter values from the URL
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
}

if (isset($_GET['branch']) && !empty($_GET['branch'])) {
    $branchFilter = $_GET['branch'];
}

if (isset($_GET['cgpa']) && !empty($_GET['cgpa'])) {
    $cgpaFilter = $_GET['cgpa'];
}

// Base SQL query
$sql = "SELECT * FROM student_info WHERE 1";

// Modify query for placement status
if ($filter == 'PLACED') {
    $sql .= " AND confirm_status = 'Confirmed'";
} elseif ($filter == 'NON_PLACED') {
    $sql .= " AND (confirm_status IS NULL OR confirm_status = 'Pending')";
}

// Append branch filter if set
if (!empty($branchFilter)) {
    $sql .= " AND branch = ?";
}

// Append CGPA filter if set
if (!empty($cgpaFilter)) {
    $sql .= " AND cgpa >= ?";
}

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
if (!empty($branchFilter) && !empty($cgpaFilter)) {
    $stmt->bind_param("sd", $branchFilter, $cgpaFilter); // 's' for string, 'd' for double (CGPA)
} elseif (!empty($branchFilter)) {
    $stmt->bind_param("s", $branchFilter);
} elseif (!empty($cgpaFilter)) {
    $stmt->bind_param("d", $cgpaFilter); // 'd' for double
}

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();
$total_students = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <style>
         /* General Body Styling */
         body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ff9a9e, #fad0c4);
            margin: 0;
            padding: 20px;
            color: #333;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 2px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f4b1b3;
            color: #333;
            font-weight: bold;
        }

        td {
            background-color: #ffe3e3;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        tr:hover td {
            background-color: #ffcccc;
            color: #000;
        }

        /* Buttons Styling */
        input[type="submit"] {
            padding: 8px 15px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        input[type="submit"]:hover {
            opacity: 0.8;
        }

        input[type="submit"][value="Update"] {
            background-color: #2ecc71;
            color: white;
        }

        input[type="submit"][value="Delete"] {
            background-color: #e74c3c;
            color: white;
        }

        input[type="submit"][value="Confirm"] {
            background-color: #3498db;
            color: white;
        }

        /* Header Styling */
        h1 {
            text-align: center;
            font-size: 2.5rem;
            color: #fff;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        /* Filter Buttons */
        .filter-buttons {
            text-align: center;
            margin-bottom: 20px;
        }

        .filter-buttons a, .filter-buttons select {
            margin: 0 10px;
            font-size: 18px;
            padding: 10px;
            background-color: green;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .filter-buttons a:hover, .filter-buttons select:hover {
            background-color: #ff6b6b;
        }

        /* Search Bar */
        .search-bar {
            margin: 20px auto;
            text-align: center;
        }

        .search-bar input[type="text"] {
            padding: 8px;
            width: 200px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-bar input[type="submit"] {
            padding: 8px 15px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .search-bar input[type="submit"]:hover {
            background-color: #27ae60;
        }
        p {
            color: green;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Student Information</h1>

<!-- Search Bar -->
<div class="search-bar">
    <form method="GET" action="show.php">
        <input type="text" name="search" placeholder="Search by USN or Name" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <input type="submit" value="Search">
    </form>
</div>

<!-- Filter Buttons -->
<div class="filter-buttons">
    <form method="GET" action="show.php" style="display: inline;">
        <!-- Branch Filter -->
        <select name="branch" onchange="this.form.submit()">
            <option value="">Select Branch</option>
            <option value="Computer Science" <?php if ($branchFilter == 'Computer Science') echo 'selected'; ?>>Computer Science</option>
            <option value="Electronics and Communication" <?php if ($branchFilter == 'Electronics and Communication') echo 'selected'; ?>>Electronics and Communication</option>
            <option value="Electrical and Electronics" <?php if ($branchFilter == 'Electrical and Electronics') echo 'selected'; ?>>Electrical and Electronics</option>
            <option value="Information Science" <?php if ($branchFilter == 'Information Science') echo 'selected'; ?>>Information Science</option>
            <option value="Civil" <?php if ($branchFilter == 'Civil') echo 'selected'; ?>>Civil</option>
            <option value="Mechanical" <?php if ($branchFilter == 'Mechanical') echo 'selected'; ?>>Mechanical</option>
            <option value="Aerospace" <?php if ($branchFilter == 'Aerospace') echo 'selected'; ?>>Aerospace</option>
            <option value="Data Science" <?php if ($branchFilter == 'Data Science') echo 'selected'; ?>>Data Science</option>
        </select>
        
        <!-- CGPA Filter -->
        <select name="cgpa" onchange="this.form.submit()">
            <option value="">Select CGPA</option>
            <option value="6" <?php if ($cgpaFilter == '6') echo 'selected'; ?>>6 and above</option>
            <option value="7" <?php if ($cgpaFilter == '7') echo 'selected'; ?>>7 and above</option>
            <option value="8" <?php if ($cgpaFilter == '8') echo 'selected'; ?>>8 and above</option>
        </select>
    </form>
    <a href="show.php?filter=ALL">All Students</a>
    <a href="placement_details.php">Placed Students</a>
    <a href="show.php?filter=NON_PLACED">Non-Placed Students</a>
</div>

<p>Total Students: <?php echo $total_students; ?></p>

<?php
// Check if records exist
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr>
            <th>USN</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Branch</th>
            <th>Date of Birth</th>
            <th>CGPA</th>
            <th>Contact Number</th>
            <th>Resume</th> 
            <th>Actions</th>
          </tr>";
    
    // Loop through and display each row of student data
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . strtoupper($row["usn"]) . "</td>";
        echo "<td>" . $row["first_name"] . "</td>";
        echo "<td>" . $row["middle_name"] . "</td>";
        echo "<td>" . $row["last_name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["branch"] . "</td>";
        echo "<td>" . $row["dob"] . "</td>";
        echo "<td>" . $row["cgpa"] . "</td>";
        echo "<td>" . $row["contact_no"] . "</td>";
        
        if ($row['offer_letter'] != NULL && !empty($row['offer_letter'])) {
            // Display Offer Letter if uploaded
            $offerLetterPath = '..upload/' . $row['offer_letter'];
            echo "<td><a href='" . $offerLetterPath . "' target='_blank'>View Offer Letter</a></td>";
        } elseif ($row['resume_path'] != NULL && !empty($row['resume_path'])){
                echo "<td><a href='" . $row['resume_path'] . "' target='_blank'>View Resume</a></td>";
        }
        else{
                echo "<td>NO Resume Uploaded</td>";
        }
        
        echo "<td>";        

        // Add Confirm Button for Pending Students
        if ($row["confirm_status"] == "Pending") {
            echo "<a href='confirm.php?usn=" . $row["usn"] . "' onclick='return confirmPlacement()'>
                    <input type='submit' value='Confirm'>
                  </a>";
        } else {
            echo "Confirmed"; // If already confirmed
        }

        // Update Link
        echo "<a href='update.php?id=" . $row["usn"] . "' onclick='return confirmUpdate()'>
                <input type='submit' value='Update'>
              </a> ";

        // Delete Link
        echo "<a href='delete.php?id=" . $row["usn"] . "' onclick='return confirmDelete()'>
                <input type='submit' value='Delete'>
              </a>";

        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No students found.";
}

$conn->close();
?>

<!-- JavaScript for confirmation on actions -->
<script>
    function confirmPlacement() {
        return confirm('Are you sure you want to confirm this placement?');
    }

    function confirmUpdate() {
        return confirm('Are you sure you want to update this record?');
    }

    function confirmDelete() {
        return confirm('Are you sure you want to delete this record?');
    }
</script>

</body>
</html>
<?php
// Close the prepared statement
$stmt->close();
?>
