<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once 'db_connection.php';

// Fetch promotions with property details
$sql = "SELECT p.*, pr.discount_percentage, pr.start_date, pr.end_date, pr.description
        FROM properties p
        INNER JOIN promotions pr ON p.id = pr.property_id";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Promotions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>View Promotions</h1>
    <?php
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Property Type</th>';
        echo '<th>Address</th>';
        echo '<th>Discount Percentage</th>';
        echo '<th>Start Date</th>';
        echo '<th>End Date</th>';
        echo '<th>Description</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['Property_Type']) . '</td>';
            echo '<td>' . htmlspecialchars($row['Address']) . '</td>';
            echo '<td>' . htmlspecialchars($row['discount_percentage']) . '</td>';
            echo '<td>' . htmlspecialchars($row['start_date']) . '</td>';
            echo '<td>' . htmlspecialchars($row['end_date']) . '</td>';
            echo '<td>' . htmlspecialchars($row['description']) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No promotions found.</p>';
    }
    ?>
</body>
</html>

<?php
$mysqli->close();
?>
