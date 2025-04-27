<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=registerplacement", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $name = $_POST['name'];
        $usn = $_POST['usn'];
        $email = $_POST['email'];
        $semester = $_POST['semester'];
        $branch = $_POST['branch'];
        $cgpa = $_POST['cgpa'];
        $company_id = $_POST['company_id'];

        // Fetch the company name and CGPA cutoff for the selected company
        $stmt = $conn->prepare("SELECT company_name, cgpa_cutoff FROM companies WHERE company_id = :company_id AND is_activated = 1");
        $stmt->execute(['company_id' => $company_id]);
        $company = $stmt->fetch();

        // Check if the company exists and if the student's CGPA meets the cutoff
        if ($company && $cgpa >= $company['cgpa_cutoff']) {
            // Count total students already registered for this company
            $stmt = $conn->prepare("SELECT COUNT(*) as total_students FROM student_registrations WHERE company_id = :company_id");
            $stmt->execute(['company_id' => $company_id]);
            $result = $stmt->fetch();
            $total_students = $result['total_students'];

            // Determine lab allocation
            $lab_names = range('A', 'G'); // Labs A to G
            $lab_index = floor($total_students / 40); // Each lab accommodates 40 students
            if ($lab_index < count($lab_names)) {
                $room_allocation = $lab_names[$lab_index];
            } else {
                // Error message if all labs are full
                echo "Registration failed: No labs available for allocation.";
                exit;
            }

            // Insert data into the table with room allocation
            $sql = "INSERT INTO student_registrations (name, usn, email, semester, branch, cgpa, company_name, company_id, room_allocation)
                    VALUES (:name, :usn, :email, :semester, :branch, :cgpa, :company_name, :company_id, :room_allocation)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'name' => $name,
                'usn' => $usn,
                'email' => $email,
                'semester' => $semester,
                'branch' => $branch,
                'cgpa' => $cgpa,
                'company_name' => $company['company_name'],
                'company_id' => $company_id,
                'room_allocation' => $room_allocation
            ]);

            // Success message with room allocation
            echo "<script>
                    alert('Registration successful! Room allocated: Lab $room_allocation.');
                    setTimeout(function() {
                        window.location.href = 'student_dashboard.php';
                    }, 2000); // Redirect after 2 seconds
                  </script>";
        } else {
            // Display error message if CGPA is below the cutoff
            echo "Registration failed: Your CGPA does not meet the company cutoff of " . $company['cgpa_cutoff'] . ".";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}
?>
