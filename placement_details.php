<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        .filter-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .apply{
            background-color: yellow ;
            margin: 0 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            outline: none;
        }
        .filter-container select {
            margin: 0 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            outline: none;
            background-color: Green ;
            color:white;

        }
        .filter-container select:focus {
            border-color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #ffffff;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        h1 {
            text-align: center;
            color: #343a40;
        }
        p {
            text-align: center;
            color: green;
            font-size: 18px;
            font-weight: bold;
        }
        .no-data-message {
            text-align: center;
            color: #ff4b5c;
            font-size: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1>Placement Details</h1>

<div class="filter-container">
    <form method="GET" action="">
        <select name="yearFilter" id="yearFilter">
            <option value="">Filter by Year</option>
            <option value="2022">2022</option>
            <option value="2023">2023</option>
            <option value="2024">2024</option>
        </select>

        <select name="branchFilter" id="branchFilter">
            <option value="">Filter by Branch</option>
            <option value="Computer Science">Computer Science</option>
            <option value="EC">Electronics and Communication</option>
            <option value="EEE">Electrical and Electronics</option>
            <option value="IS">Information Science</option>
            <option value="Data Science">Data Science</option>
            <option value="AI/ML">Artificial Intelligence / Machine Learning</option>
            <option value="Mechanical">Mechanical</option>
            <option value="Civil">Civil</option>
            <option value="Aerospace">Aerospace</option>
        </select>

        <select name="companyFilter" id="companyFilter">
            <option value="">Filter by Company</option>
            <option value="Infosys">Infosys</option>
            <option value="Wipro">Wipro</option>
            <option value="TCS">TCS</option>
        </select>

        <select name="packageFilter" id="packageFilter">
            <option value="">Filter by Package</option>
            <option value="4 LPA">4 LPA</option>
            <option value="6 LPA">6 LPA</option>
            <option value="12 LPA">12 LPA</option>
        </select>

        <button type="submit"  class="apply">Apply Filters</button>
    </form>
</div>

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

// Collect filter values
$yearFilter = isset($_GET['yearFilter']) ? $_GET['yearFilter'] : '';
$branchFilter = isset($_GET['branchFilter']) ? $_GET['branchFilter'] : '';
$companyFilter = isset($_GET['companyFilter']) ? $_GET['companyFilter'] : '';
$packageFilter = isset($_GET['packageFilter']) ? $_GET['packageFilter'] : '';

// Construct the SQL query based on the filters
$sql = "SELECT 
            pd.usn, 
            pd.student_name, 
            pd.contact_number, 
            pd.company_name, 
            pd.package, 
            pd.year_of_selection, 
            si.branch,
            si.resume_path 
        FROM 
            placement_details pd
        INNER JOIN 
            student_info si 
        ON 
            pd.usn = si.usn
        WHERE 
            1=1";

// Apply filters to the SQL query if they are set
if (!empty($yearFilter)) {
    $sql .= " AND pd.year_of_selection = '" . $conn->real_escape_string($yearFilter) . "'";
}
if (!empty($branchFilter)) {
    $sql .= " AND si.branch = '" . $conn->real_escape_string($branchFilter) . "'";
}
if (!empty($companyFilter)) {
    $sql .= " AND pd.company_name = '" . $conn->real_escape_string($companyFilter) . "'";
}
if (!empty($packageFilter)) {
    $sql .= " AND pd.package = '" . $conn->real_escape_string($packageFilter) . "'";
}
$sql .= " ORDER BY pd.company_name";
// Execute the query
$result = $conn->query($sql);

// Check if there are any results
$total_students_placed = $result->num_rows;

// Display the total count
echo "<p>Total Students Placed: $total_students_placed</p>";

if ($total_students_placed > 0) {
    $currentCompany = '';
    // Start displaying the data
    echo "<table id='placementTable'>";
    echo "<tr>
            <th>USN</th>
            <th>Student Name</th>
            <th>Contact Number</th>
            <th>Company Name</th>
            <th>Package</th>
            <th>Year of Selection</th>
            <th>Branch</th>
            <th>Resume</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        // Display a new heading when the company changes
        if ($currentCompany !== $row['company_name']) {
            $currentCompany = $row['company_name'];
            echo "<tr><td colspan='8' style='background-color:orange; color: #ffffff; font-size: 20px; text-align: center;'> " . htmlspecialchars($currentCompany) . "</td></tr>";
        }

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['usn']) . "</td>";
        echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['contact_number']) . "</td>";
        echo "<td>" . htmlspecialchars($row['company_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['package']) . "</td>";
        echo "<td>" . htmlspecialchars($row['year_of_selection']) . "</td>";
        echo "<td>" . htmlspecialchars($row['branch']) . "</td>";
        echo "<td>";
        if (!empty($row['resume_path'])) {
            echo "<a href='" . htmlspecialchars($row['resume_path']) . "' target='_blank'>View Resume</a>";
        } else {
            echo "No Resume Uploaded";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No placement details found.</p>";
}

// Close the connection
$conn->close();
?>

</body>
</html>
