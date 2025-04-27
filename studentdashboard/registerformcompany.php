<?php
// Database connection
$conn = new PDO("mysql:host=localhost;dbname=registerplacement", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Assuming the student's email is stored in the session
session_start();
$email = $_SESSION['email']; // This assumes the email is stored in the session after login

// Check if the student is already placed
$sql = "SELECT * FROM student_info WHERE email = :email AND confirm_status = 'Confirmed'";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();
$student_placed = $stmt->rowCount() > 0; // If the student has been placed, this will be true

// Fetch only activated companies and their CGPA cutoff
$sql = "SELECT company_id, company_name, cgpa_cutoff FROM companies WHERE is_activated = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$active_companies = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <style>
    /* Your existing styles */
    body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(to right, #e0eafc, #cfdef3);
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0;
    padding: 20px;
}

h2 {
    color: #2c3e50;
    font-size: 2.5em;
    margin-bottom: 20px;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
}

form {
    border-radius: 12px;
    padding: 30px;
    margin-top: 20px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
}

form:hover {
    transform: scale(1.02);
}

label {
    font-size: 1.1em;
    font-weight: 600;
    margin-bottom: 10px;
    display: block;
    color: #34495e;
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
}

input, select, button {
    width: 85%;
    padding: 12px;
    margin: 10px 0 20px 0;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1em;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

input:focus, select:focus {
    border-color: #3498db;
    box-shadow: 0 0 8px rgba(52, 152, 219, 0.3);
    outline: none;
}

button {
    background: linear-gradient(to right, #3498db, #2980b9);
    color: white;
    font-size: 1.1em;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s;
    border: none;
}

button:hover {
    background: linear-gradient(to right, #2980b9, #1c6ca1);
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

button:active {
    transform: scale(0.98);
}

select {
    background-color: #f9f9f9;
    color: #555;
}

option {
    padding: 10px;
    background-color: #f5f5f5;
    color: #333;
}

::placeholder {
    color: #aaa;
    font-style: italic;
}

form::after {
    content: "";
    display: block;
    margin: 30px auto;
    width: 50px;
    height: 4px;
    background-color:green;
    border-radius: 2px;
    box-shadow: 0 4px 8px rgba(52, 152, 219, 0.4);
}
    </style>
</head>
<body>

<h2>Student Registration Form</h2>

<?php if ($student_placed): ?>
    <p>You have already been placed and cannot register for another company.</p>
<?php else: ?>
    <form action="register_for_company.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="usn">USN:</label>
        <input type="text" id="usn" name="usn" required>

        <label for="email">Student Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required readonly>

        <label for="semester">Semester:</label>
        <input type="number" id="semester" name="semester" min="1" max="8" required>

        <label for="branch">Branch:</label><br>
        <select id="branch" name="branch" required>
            <option value="Computer Science">Computer Science</option>
            <option value="Electronics and Communication">Electronics and Communication</option>
            <option value="Electrical and Electronics">Electrical and Electronics</option>
            <option value="Information Science">Information Science</option>
            <option value="Civil">Civil</option>
            <option value="Mechanical">Mechanical</option>
            <option value="Aerospace">Aerospace</option>
            <option value="Data Science">Data Science</option>
        </select><br><br>

        <label for="cgpa">CGPA:</label>
        <input type="number" step="0.01" id="cgpa" name="cgpa" required>

        <label for="company_name">Company Name:</label>
        <select name="company_id" id="company_name" required>
            <option value="">Select a Company</option>
            <?php foreach ($active_companies as $company): ?>
                <option value="<?php echo $company['company_id']; ?>" data-cgpa-cutoff="<?php echo $company['cgpa_cutoff']; ?>">
                    <?php echo htmlspecialchars($company['company_name']); ?> (Cutoff: <?php echo $company['cgpa_cutoff']; ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <div class="error" id="cgpaError" style="display:none;">CGPA does not meet the company cutoff.</div>

        <button type="submit" <?php echo $student_placed ? 'disabled' : ''; ?>>Register</button>
    </form>
<?php endif; ?>

<script>
    const cgpaInput = document.getElementById('cgpa');
    const companySelect = document.getElementById('company_name');
    const cgpaError = document.getElementById('cgpaError');

    companySelect.addEventListener('change', function() {
        const selectedOption = companySelect.options[companySelect.selectedIndex];
        const cgpaCutoff = parseFloat(selectedOption.getAttribute('data-cgpa-cutoff'));
        const studentCgpa = parseFloat(cgpaInput.value);

        if (studentCgpa < cgpaCutoff) {
            cgpaError.style.display = 'block';
            document.querySelector('button[type="submit"]').disabled = true;
        } else {
            cgpaError.style.display = 'none';
            document.querySelector('button[type="submit"]').disabled = false;
        }
    });

    cgpaInput.addEventListener('input', function() {
        companySelect.dispatchEvent(new Event('change'));
    });
</script>

</body>
</html>
