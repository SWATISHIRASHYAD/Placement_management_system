<?php
// Include the database connection file
include 'db.php';

// SQL query to fetch active companies
$sql = "SELECT company_id, company_name, description, website, status FROM companies WHERE status = 'Active'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Company Information</title>
  <style>
    /* General Styles */
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f4f8fc;
      color: #333;
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
      font-size: 2.5rem;
    }

    /* Grid Layout */
    .container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    /* Card Styles */
    .card {
      background-color: #fff;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      display: flex;
      flex-direction: column;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card h2 {
      font-size: 1.5rem;
      color: #4CAF50;
      margin: 15px 20px 5px;
      text-align: center;
    }

    .card p {
      margin: 0 20px 20px;
      font-size: 1rem;
      color: #666;
      text-align: justify;
    }

    .card a {
      margin: auto 20px 20px;
      padding: 10px 20px;
      background-color: #007BFF;
      color: #fff;
      text-align: center;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      align-self: flex-start;
      transition: background-color 0.3s ease;
    }

    .card a:hover {
      background-color: #0056b3;
    }

    /* Card Image Section */
    .card-header {
      background: linear-gradient(to bottom right, #4CAF50, #007BFF);
      height: 120px;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
      font-size: 2rem;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h1>Active Companies</h1>
  <div class="container">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
          <div class="card-header">
            <?php echo htmlspecialchars(substr($row['company_name'], 0, 1)); ?>
          </div>
          <h2><?php echo htmlspecialchars($row['company_name']); ?></h2>
          <p><?php echo htmlspecialchars($row['description']); ?></p>
          <a href="<?php echo htmlspecialchars($row['website']); ?>" target="_blank">Visit Website</a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No active companies available.</p>
    <?php endif; ?>
  </div>

  <?php
  // Close the database connection
  $conn->close();
  ?>
</body>
</html>
