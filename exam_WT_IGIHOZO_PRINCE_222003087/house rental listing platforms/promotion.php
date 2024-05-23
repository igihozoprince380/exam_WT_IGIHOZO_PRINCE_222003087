<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once 'db_connection.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input (you should add more validation as needed)
    $property_id = $_POST['property_id'];
    $discount_percentage = $_POST['discount_percentage'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $description = $_POST['description'];

    // Insert promotion into database
    $sql = "INSERT INTO promotions (property_id, discount_percentage, start_date, end_date, description)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("issss", $property_id, $discount_percentage, $start_date, $end_date, $description);

    if ($stmt->execute()) {
        echo '<p>Promotion added successfully.</p>';
    } else {
        echo '<p>Error adding promotion: ' . $mysqli->error . '</p>';
    }

    $stmt->close();
}

// Retrieve properties from the database
$sql_properties = 'SELECT id, Property_Type, Address FROM properties';
$result_properties = $mysqli->query($sql_properties);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Promotion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        label, input, select, textarea {
            display: block;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Add Promotion</h1>
    <form method="POST" action="">
        <label for="property_id">Select Property:</label>
        <select id="property_id" name="property_id">
            <?php
            if ($result_properties->num_rows > 0) {
                while ($row = $result_properties->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['Property_Type'] . ' - ' . $row['Address']) . '</option>';
                }
            } else {
                echo '<option value="">No properties found</option>';
            }
            ?>
        </select>
        <label for="discount_percentage">Discount Percentage:</label>
        <input type="number" id="discount_percentage" name="discount_percentage" min="0" max="100" step="0.01" required>
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required>
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>
        <input type="submit" value="Add Promotion">
    </form>
</body>
</html>

<?php
$mysqli->close();
?>
