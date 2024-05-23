<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Furniture Item</title>
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
        <h2>Purchase Furniture Item</h2>

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

        // Check if item_id is set in GET parameters
        if (isset($_GET['item_id'])) {
            $item_id = $_GET['item_id'];

            // Retrieve furniture item details
            $sql = "SELECT id, name, price, quantity, description, photo FROM furniture WHERE id = $item_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Display item details
                $row = $result->fetch_assoc();
                echo '<div>';
                echo '<strong>ID:</strong> ' . $row['id'] . '<br>';
                echo '<strong>Name:</strong> ' . $row['name'] . '<br>';
                echo '<strong>Price:</strong> $' . $row['price'] . '<br>';
                echo '<strong>Quantity:</strong> ' . $row['quantity'] . '<br>';
                echo '<strong>Description:</strong> ' . $row['description'] . '<br>';
                echo '<img src="' . $row['photo'] . '" style="max-width: 100px; max-height: 100px;" alt="Furniture Photo"><br>';

                // Add purchase form
                echo '<form action="process_purchase.php" method="POST">';
                echo '<input type="hidden" name="item_id" value="' . $row['id'] . '">';
                echo '<input type="hidden" name="item_name" value="' . $row['name'] . '">';
                echo '<input type="hidden" name="item_price" value="' . $row['price'] . '">';
                echo '<button type="submit" style="margin-top: 10px;">Confirm Purchase</button>';
                echo '</form>';

                // Example PayPal button (replace with your actual PayPal integration code)
                echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';
                echo '<input type="hidden" name="cmd" value="_xclick">';
                echo '<input type="hidden" name="business" value="your_paypal_email@example.com">';
                echo '<input type="hidden" name="item_name" value="' . $row['name'] . '">';
                echo '<input type="hidden" name="amount" value="' . $row['price'] . '">';
                echo '<input type="hidden" name="currency_code" value="USD">';
                echo '<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online">';
                echo '<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">';
                echo '</form>';

                echo '</div>';
            } else {
                echo '<p>Furniture item not found.</p>';
            }
        } else {
            echo '<p>No item selected for purchase.</p>';
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</body>
</html>
