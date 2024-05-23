<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Furniture Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        form {
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
        }

        input[type=text], input[type=number], textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        .container {
            padding: 16px;
        }

        .alert {
            padding: 20px;
            background-color: #4CAF50;
            color: white;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .error {
            padding: 20px;
            background-color: #f44336;
            color: white;
            margin-bottom: 15px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h2>Add Furniture Item</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="container">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br><br>

            <label for="price">Price:</label><br>
            <input type="text" id="price" name="price" required><br><br>

            <label for="quantity">Quantity:</label><br>
            <input type="number" id="quantity" name="quantity" required><br><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4"></textarea><br><br>

            <label for="photo">Photo URL:</label><br>
            <input type="text" id="photo" name="photo"><br><br>

            <input type="submit" value="Add Furniture">
        </div>
    </form>

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

    // Check if form was submitted via POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Prepare data from form (sanitize input if necessary)
        $name = $_POST["name"];
        $price = $_POST["price"];
        $quantity = $_POST["quantity"];
        $description = $_POST["description"];
        $photo = $_POST["photo"];

        // SQL query to insert data into furniture table
        $sql = "INSERT INTO furniture (name, price, quantity, description, photo)
                VALUES (?, ?, ?, ?, ?)";

        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdiss", $name, $price, $quantity, $description, $photo); // Changed 'sdiss' to 'sdiss'

        // Execute SQL query and check for success
        if ($stmt->execute()) {
            echo '<div class="alert">New record created successfully</div>';
        } else {
            echo '<div class="error">Error: ' . $sql . '<br>' . $conn->error . '</div>';
        }

        // Close statement
        $stmt->close();
    }

    // Close database connection
    $conn->close();
    ?>
</body>
</html>
