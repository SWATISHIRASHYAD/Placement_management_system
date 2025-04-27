<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Registrations</title>
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

        /* Styles for each company container */
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

        /* List of students */
        ul {
            list-style-type: none;
            padding: 0;
            margin: 10px 0;
        }

        ul li {
            background-color: #f0f8ff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin: 5px 0;
            font-size: 1em;
            color: #555;
        }

        /* Message for no registrations */
        p {
            font-size: 1em;
            color: #666;
        }
    </style>
</head>
<body>

<h2>Company Registrations</h2>

<?php foreach ($registrations as $company_name => $students): ?>
    <div class="company">
        <h3><?php echo htmlspecialchars($company_name); ?></h3>
        <?php if (count($students) > 0): ?>
            <ul>
                <?php foreach ($students as $student): ?>
                    <li><?php echo htmlspecialchars($student['student_name']); ?> (ID: <?php echo htmlspecialchars($student['student_id']); ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No registrations yet.</p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

</body>
</html>
