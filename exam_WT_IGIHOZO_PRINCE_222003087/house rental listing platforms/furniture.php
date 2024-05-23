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
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .furniture-item {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .furniture-item img {
            max-width: 100px;
            max-height: 100px;
            margin-bottom: 10px;
        }
        .furniture-item h3 {
            margin-top: 0;
        }
        .furniture-item p {
            margin-bottom: 10px;
        }
        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #cc0000;
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

        // Check if delete request is sent
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
            $delete_id = $_POST['delete_id'];

            // SQL query to delete furniture item
            $sql_delete = "DELETE FROM furniture WHERE id = ?";
            $stmt = $conn->prepare($sql_delete);
            $stmt->bind_param("i", $delete_id);

            if ($stmt->execute()) {
                echo '<div class="alert">Furniture item deleted successfully.</div>';
            } else {
                echo '<div class="error">Error deleting furniture item: ' . $conn->error . '</div>';
            }

            $stmt->close();
        }

        // SQL query to fetch furniture items
        $sql = "SELECT id, name, price, quantity, description, photo FROM furniture";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<div class="furniture-item">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p><strong>Price:</strong> $' . $row['price'] . '</p>';
                echo '<p><strong>Quantity:</strong> ' . $row['quantity'] . '</p>';
                echo '<p><strong>Description:</strong><br>' . nl2br(htmlspecialchars($row['description'])) . '</p>';
                echo '<img src="' . $row['photo'] . '" alt="Furniture Photo">';
                
                // Delete button form
                echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="POST">';
                echo '<input type="hidden" name="delete_id" value="' . $row['id'] . '">';
                echo '<button type="submit" class="delete-btn">Delete</button>';
                echo '</form>';
                
                echo '</div>';
            }
        } else {
            echo '<p>No furniture items found.</p>';
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</body>
</html>
