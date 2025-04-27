<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Company</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Form Container */
        .form-container {
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        /* Header Styling */
        h2 {
            font-size: 1.8em;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Label Styling */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        /* Input and Textarea Styling */
        input[type="text"],input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            resize: vertical;
        }

        /* Button Styling */
        button {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New Company</h2>

    <form action="http://localhost:8008/placement management system/companyregisteration/insert_company.php" method="POST">
        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="cgpa_cutoff">CGPA Cutoff:</label>
        <input type="number" step="0.01" id="cgpa_cutoff" name="cgpa_cutoff" required>

        <button type="submit">Add Company</button>
    </form>
</div>
    </body
    
