<?php
// Database connection
$conn = new PDO("mysql:host=localhost;dbname=registerplacement", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch all companies from the database
$sql = "SELECT * FROM companies";
$stmt = $conn->prepare($sql);
$stmt->execute();
$companies = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Company Management</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        
        /* Container for the main content */
        .container {
            width: 80%;
            margin: 0 auto;
            padding-top: 20px;
        }

        /* Header styling */
        h2 {
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
        }

        /* Link for adding new company */
        a.add-company {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        /* Individual company card styling */
        .company {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Company name and description styling */
        .company h3 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 10px;
        }

        .company p {
            font-size: 1em;
            color: #555;
            margin-bottom: 10px;
        }

        /* Status styling */
        .company .status {
            font-weight: bold;
            color: #4CAF50;
        }
        .company .deactivated {
            color: #FF0000;
        }

        /* Action buttons */
        .company a {
            display: inline-block;
            margin-right: 10px;
            padding: 8px 12px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .company a:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        // JavaScript for the confirmation popup
        function confirmDeactivation(companyId) {
            const confirmation = confirm("Are you sure you want to deactivate this company? This will delete all registrations for this company.");
            if (confirmation) {
                window.location.href = 'toggle_activation.php?company_id=' + companyId + '&action=deactivate';
            }
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Admin Dashboard - Company Management</h2>

    <!-- Link to add a new company -->
    <a href="http://localhost:8008/placement management system/companyregisteration/add_company.php" class="add-company">Add New Company</a>

    <!-- Display companies with activate, deactivate, and edit options -->
    <?php foreach ($companies as $company): ?>
        <div class="company">
            <h3><?php echo htmlspecialchars($company['company_name']); ?></h3>
            <p><?php echo htmlspecialchars($company['description']); ?></p>
            <p><?php echo htmlspecialchars($company['cgpa_cutoff']); ?></p>
            <p class="status">
                Status: <span class="<?php echo $company['is_activated'] == 1 ? 'activated' : 'deactivated'; ?>">
                    <?php echo $company['is_activated'] == 1 ? 'Activated' : 'Deactivated'; ?>
                </span>
            </p>
            <a href="http://localhost:8008/placement management system/companyregisteration/edit_company.php?company_id=<?php echo $company['company_id']; ?>">Edit</a>

            <!-- Deactivate/Activate button -->
            <?php if ($company['is_activated'] == 1): ?>
                <!-- If the company is active, show deactivate button -->
                <a href="javascript:void(0);" onclick="confirmDeactivation(<?php echo $company['company_id']; ?>)">Deactivate</a>
            <?php else: ?>
                <!-- If the company is deactivated, show activate button -->
                <a href="http://localhost:8008/placement management system/companyregisteration/toggle_activation.php?company_id=<?php echo $company['company_id']; ?>">Activate</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
