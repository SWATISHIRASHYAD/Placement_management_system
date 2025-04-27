<?php
include 'db.php'; // Include your database connection

// Get filter values from the URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'ALL';
$branchFilter = isset($_GET['branch']) ? $_GET['branch'] : '';
$cgpaFilter = isset($_GET['cgpa']) ? $_GET['cgpa'] : '';

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

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="students_list.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Write column headers
fputcsv($output, ['USN', 'First Name', 'Middle Name', 'Last Name', 'Email', 'Branch', 'DOB', 'CGPA', 'Contact No', 'Status']);

// Write data rows
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        strtoupper($row["usn"]),
        $row["first_name"],
        $row["middle_name"],
        $row["last_name"],
        $row["email"],
        $row["branch"],
        $row["dob"],
        $row["cgpa"],
        $row["contact_no"],
        $row["confirm_status"] ? $row["confirm_status"] : 'Pending'
    ]);
}

// Close output stream
fclose($output);
exit;
?>
