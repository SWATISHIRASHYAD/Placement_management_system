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

// Construct the SQL query
$sql = "SELECT 
            pd.usn, 
            pd.student_name, 
            pd.contact_number, 
            pd.company_name, 
            pd.package, 
            pd.year_of_selection, 
            si.branch
        FROM 
            placement_details pd
        INNER JOIN 
            student_info si 
        ON 
            pd.usn = si.usn
        WHERE 
            1=1";

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

$result = $conn->query($sql);

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="placement_details.csv"');

$output = fopen('php://output', 'w');

// Write the header row
fputcsv($output, ['USN', 'Student Name', 'Contact Number', 'Company Name', 'Package', 'Year of Selection', 'Branch']);

// Write data rows
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['usn'],
            $row['student_name'],
            $row['contact_number'],
            $row['company_name'],
            $row['package'],
            $row['year_of_selection'],
            $row['branch']
        ]);
    }
}

// Close the file pointer and connection
fclose($output);
$conn->close();
?>
