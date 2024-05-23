<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once 'db_connection.php';

// Function to sanitize and validate input
function sanitizeInput($input) {
    global $mysqli;
    return htmlspecialchars(trim($mysqli->real_escape_string($input)));
}

// Retrieve properties from the database
$sql = 'SELECT * FROM properties';
$result = $mysqli->query($sql);

// Check if there are properties
if ($result === false) {
    echo 'Error executing query: ' . $mysqli->error;
} else {
    // Initialize variables
    $properties = [];

    // Fetch all properties into an array
    while ($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }

    // Close the result set
    $result->close();
}

// Close the database connection
$mysqli->close();
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

        .action-select {
            margin-bottom: 10px;
        }

        .action-select select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
.action-select select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    color:darkgreen; 
    background-color:yellow; 
}

.action-select select option {
    padding: 5px;
    color:darkgreen; 
    background-color:yellow; 
}

.action-select select option:hover {
    background-color:blue; 
}

    </style>
</head>
<body>
    <header>
        <h1>View Properties</h1>
    </header>
    
    <main>
        <div class="action-select">
            <select onchange="if (this.value) window.location.href=this.value;">
                <option value="">AVAILABLE BUY PROPERTIES,RENT PROPERTIES, BOOKING PROPERTIES</option>
                <option value="my_listings.php?action=buy">Buy</option>
                <option value="my_listings.php?action=rent">Rent</option>
                <option value="my_listings.php?action=booking">Booking</option>
            </select>
        </div>

        <?php
        // Check if action parameter is set and valid
        if (isset($_GET['action']) && in_array($_GET['action'], ['buy', 'rent', 'booking'])) {
            $action = $_GET['action'];

            // Filter properties based on action
            $filteredProperties = array_filter($properties, function($property) use ($action) {
                return $property['Price_Type'] === $action;
            });

            // Display filtered properties
            if (!empty($filteredProperties)) {
                foreach ($filteredProperties as $property) {
                    echo '<div class="property">';
                    echo '<h2>' . htmlspecialchars($property['Property_Type']) . '</h2>';
                    echo '<p><strong>Address:</strong> ' . htmlspecialchars($property['Address']) . '</p>';
                    echo '<p><strong>Description:</strong> ' . htmlspecialchars($property['Description']) . '</p>';
                    echo '<p><strong>Price:</strong> $' . htmlspecialchars($property['Price']) . '</p>';
                    echo '<img src="' . htmlspecialchars($property['Photo']) . '" alt="Property Photo">';
                    echo '</div>';
                }
            } else {
                echo '<p>No properties found for ' . ucfirst($action) . '.</p>';
            }
        } else {
            // Display all properties initially
            if (!empty($properties)) {
                foreach ($properties as $property) {
                    echo '<div class="property">';
                    echo '<h2>' . htmlspecialchars($property['Property_Type']) . '</h2>';
                    echo '<p><strong>Address:</strong> ' . htmlspecialchars($property['Address']) . '</p>';
                    echo '<p><strong>Description:</strong> ' . htmlspecialchars($property['Description']) . '</p>';
                    echo '<p><strong>Price:</strong> $' . htmlspecialchars($property['Price']) . '</p>';
                    echo '<img src="' . htmlspecialchars($property['Photo']) . '" alt="Property Photo">';
                    echo '</div>';
                }
            } else {
                echo '<p>No properties found.</p>';
            }
        }
        ?>
    </main>
</body>
</html>
