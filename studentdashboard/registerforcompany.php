<?php
// Database connection
$conn = new PDO("mysql:host=localhost;dbname=registerplacement", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch only active companies
$sql = "SELECT * FROM companies WHERE is_activated = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$active_companies = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Companies for Registration</title>
    <style>
        /* Basic styles for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #2c3e50;
            font-size: 2em;
            margin-bottom: 20px;
        }

        /* Styles for the company card container */
        .company {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .company:hover {
            transform: translateY(-5px);
        }

        /* Company name */
        .company h3 {
            font-size: 1.5em;
            color: #34495e;
            margin-top: 0;
        }

        /* Company description */
        .company p {
            color: #555;
            margin: 10px 0;
        }

        /* Register link button */
        .company a {
            display: inline-block;
            padding: 8px 12px;
            background-color: #2980b9;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.2s;
        }

        .company a:hover {
            background-color: #1c6ea4;
        }

        /* Message when no companies are available */
        p {
            font-size: 1.1em;
            color: #666;
        }
    </style>
</head>
<body>

<h2 style=" background-color:green; color:white;">Available Companies for Registration</h2>

<?php if (count($active_companies) > 0): ?>
    <?php foreach ($active_companies as $company): ?>
        <div class="company">
            <h3><?php echo htmlspecialchars($company['company_name']); ?></h3>
            <p><?php echo htmlspecialchars($company['description']); ?></p>
            <h5><?php echo "CGPA CUTOFF:"; ?></h5>
            <h5><?php echo htmlspecialchars($company['cgpa_cutoff']); ?></h5>
            <a href="http://localhost:8008/placement management system/studentdashboard/registerformcompany.php">Apply Now</a>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No companies are available for registration at the moment.</p>
<?php endif; ?>

</body>
</html>
