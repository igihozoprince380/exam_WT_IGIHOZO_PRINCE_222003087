<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once 'db_connection.php';

// Retrieve properties from the database
$sql = 'SELECT * FROM properties';
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Properties</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        main {
            padding: 20px;
        }

        .property {
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 10px;
        }

        .property h2 {
            margin-top: 0;
        }

        .property img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <header>
        <h1>View Properties</h1>
    </header>
    
    <main>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="property">';
                echo '<h2>' . htmlspecialchars($row['Property_Type']) . '</h2>';
                echo '<p><strong>Address:</strong> ' . htmlspecialchars($row['Address']) . '</p>';
                echo '<p><strong>Description:</strong> ' . htmlspecialchars($row['Description']) . '</p>';
                echo '<p><strong>Price:</strong> $' . htmlspecialchars($row['Price']) . '</p>';
                echo '<img src="' . htmlspecialchars($row['Photo']) . '" alt="Property Photo">';
                echo '<a href="edit_property.php?id=' . $row['id'] . '">Edit</a> | ';
                echo '<a href="delete_property.php?id=' . $row['id'] . '">Delete</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No properties found.</p>';
        }

        $mysqli->close();
        ?>
    </main>
</body>
</html>
