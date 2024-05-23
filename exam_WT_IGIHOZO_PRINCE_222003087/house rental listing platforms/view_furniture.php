<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Furniture Items</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>View Furniture Items</h2>
        <?php
        // Database connection parameters
        $servername = "localhost";
        $username = "root"; 
        $password = ""; 
        $database = "hrlp";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to fetch furniture items
        $sql = "SELECT id, name, price, quantity, description, photo FROM furniture";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<div>'; // Use a div instead of a table
            echo '<ul style="list-style-type:none;">';
            while ($row = $result->fetch_assoc()) {
                echo '<li style="margin-bottom: 20px;">';
                echo '<strong>ID:</strong> ' . $row['id'] . '<br>';
                echo '<strong>Name:</strong> ' . $row['name'] . '<br>';
                echo '<strong>Price:</strong> $' . $row['price'] . '<br>';
                echo '<strong>Quantity:</strong> ' . $row['quantity'] . '<br>';
                echo '<strong>Description:</strong> ' . $row['description'] . '<br>';
                echo '<img src="' . $row['photo'] . '" style="max-width: 100px; max-height: 100px;" alt="Furniture Photo"><br>';
                echo '<form action="purchase.php" method="GET">';
                echo '<input type="hidden" name="item_id" value="' . $row['id'] . '">';
                echo '<button type="submit" style="margin-top: 10px;">Buy</button>'; // Buy button
                echo '</form>';
                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<p>No furniture items found.</p>';
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</body>
</html>
