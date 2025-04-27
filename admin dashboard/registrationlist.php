<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle search query and branch filter
    $search_query = $_POST['search'] ?? '';
    $branch_filter = $_POST['branch'] ?? '';  // Get branch filter
} else {
    $search_query = '';
    $branch_filter = '';

}


try {
    $conn = new PDO("mysql:host=localhost;dbname=registerplacement", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define how many students to show per page
    $students_per_page = 20;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $students_per_page;

    // Modify query to include both the search term and branch filter independently
    $sql = "SELECT * FROM student_registrations WHERE 
            (name LIKE :search OR usn LIKE :search OR cgpa LIKE :search)
            AND (branch LIKE :branch)
            ORDER BY name LIMIT :offset, :students_per_page";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':search', "%$search_query%", PDO::PARAM_STR);
    $stmt->bindValue(':branch', "%$branch_filter%", PDO::PARAM_STR);  // Bind branch filter independently
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':students_per_page', $students_per_page, PDO::PARAM_INT);
    $stmt->execute();
    
    $students = $stmt->fetchAll();
    
    // Get total number of students
    $count_stmt = $conn->prepare("SELECT COUNT(*) FROM student_registrations WHERE 
                                  (name LIKE :search OR usn LIKE :search OR cgpa LIKE :search)
                                  AND (branch LIKE :branch)");
    $count_stmt->bindValue(':search', "%$search_query%", PDO::PARAM_STR);
    $count_stmt->bindValue(':branch', "%$branch_filter%", PDO::PARAM_STR);  // Bind branch filter here as well
    $count_stmt->execute();
    $total_students = $count_stmt->fetchColumn();
    $total_pages = ceil($total_students / $students_per_page);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Info</title>
    <style>
        /* Styling for the entire body */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f4f8;
    color: #333;
    margin: 0;
    padding: 20px;
}

/* Main container to center content */
.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    text-align: center;
}

/* Header styling */
h1 {
    font-size: 2.5em;
    color: #2c3e50;
    margin-bottom: 20px;
    text-shadow: 1px 1px #e1e1e1;
}

/* Search form styling */
form {
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
}

form input, form select {
    padding: 10px;
    font-size: 1em;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-right: 10px;
    width: 300px;
}

form button {
    padding: 10px 20px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1em;
    cursor: pointer;
}

form button:hover {
    background-color: #2980b9;
}

/* Section for each company */
.company-section {
    background-color: #ffffff;
    border: 2px solid #2980b9;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

/* Title for company */
.company-section h2 {
    color: #2980b9;
    font-size: 2em;
    margin-bottom: 15px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;
    text-align: center;
}

/* Grid layout for student cards */
.student-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

/* Individual student card styling */
.student-card {
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
}

.student-card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.student-card p {
    margin: 8px 0;
    font-size: 1em;
    color: #333;
}

.student-card p strong {
    color: #555;
}

    </style>
</head>
<body>

    <div class="container">
        <h1>Student Registrations</h1>

        <!-- Search and Branch Filter Form -->
       
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Search by Name, USN, or CGPA" value="<?php echo htmlspecialchars($search_query); ?>" required>
            
            <!-- Branch Filter Dropdown -->
            

            <button type="submit">Search</button>
        </form>
        <!-- Branch Filter Section (Separate from the search form) -->
        <div class="branch-filter">
            <form method="POST" action="">
                <select name="branch" onchange="this.form.submit()">
                    <option value="">Select Branch</option>
                    <option value="Computer Science" <?php echo $branch_filter == 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                    <option value="Electronics and Communication" <?php echo $branch_filter == 'Electronics and Communication' ? 'selected' : ''; ?>>Electronics and Communication</option>
                    <option value="Electrical and Electronics" <?php echo $branch_filter == 'Electrical and Electronics' ? 'selected' : ''; ?>>Electrical and Electronics</option>
                    <option value="Information Science" <?php echo $branch_filter == 'Information Science' ? 'selected' : ''; ?>>Information Science</option>
                    <option value="Civil" <?php echo $branch_filter == 'Civil' ? 'selected' : ''; ?>>Civil</option>
                    <option value="Mechanical" <?php echo $branch_filter == 'Mechanical' ? 'selected' : ''; ?>>Mechanical</option>
                    <option value="Aerospace" <?php echo $branch_filter == 'Aerospace' ? 'selected' : ''; ?>>Aerospace</option>
                    <option value="Data Science" <?php echo $branch_filter == 'Data Science' ? 'selected' : ''; ?>>Data Science</option>
                </select>
            </form>
        </div>

        <!-- Display Students -->
        <?php if ($students): ?>
            <div class="pagination">
                <p>Page <?php echo $current_page; ?> of <?php echo $total_pages; ?></p>
                <div class="page-links">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search_query); ?>&branch=<?php echo htmlspecialchars($branch_filter); ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                </div>
            </div>

            <?php
            // Group students by company
            $companies = [];
            foreach ($students as $student) {
                $companies[$student['company_name']][] = $student;
            }
            ?>

            <!-- Display Student Cards by Company -->
            <?php foreach ($companies as $company_name => $company_students): ?>
                <div class="company-section">
                    <h2><?php echo htmlspecialchars($company_name); ?></h2>
                    <div class="student-list">
                        <?php foreach ($company_students as $student): ?>
                            <div class="student-card">
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                                <p><strong>USN:</strong> <?php echo htmlspecialchars($student['usn']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                                <p><strong>Semester:</strong> <?php echo htmlspecialchars($student['semester']); ?></p>
                                <p><strong>Branch:</strong> <?php echo htmlspecialchars($student['branch']); ?></p>
                                <p><strong>CGPA:</strong> <?php echo htmlspecialchars($student['cgpa']); ?></p>
                                <p><strong>Company:</strong> <?php echo htmlspecialchars($student['company_name']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p>No student registrations found for the selected search query and branch filter.</p>
        <?php endif; ?>
    </div>

</body>
</html>
