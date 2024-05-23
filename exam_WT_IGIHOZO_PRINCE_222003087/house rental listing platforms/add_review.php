<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 400px;
            max-width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="number"], textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .message {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Review</h2>
        <form action="add_review.php" method="post">
            <label for="property_type">Property Type:</label>
            <select id="property_type" name="property_type" required>
                <option value="">Select Property Type</option>
                <?php
                // Database configuration
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "hrlp";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch distinct property types
                $sql = "SELECT DISTINCT Property_Type FROM properties";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['Property_Type'] . "'>" . $row['Property_Type'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No Property Types Available</option>";
                }

                $conn->close();
                ?>
            </select>
            
            <label for="rating">Rating:</label>
            <input type="number" id="rating" name="rating" min="1" max="5" required>
            
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" rows="4" cols="50" required></textarea>
            
            <input type="submit" name="submit" value="Submit">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Database configuration
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "hrlp";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get form data
            $property_type = $_POST['property_type'];
            $rating = $_POST['rating'];
            $comment = $_POST['comment'];

            // Fetch property id based on property type
            $stmt = $conn->prepare("SELECT id FROM properties WHERE Property_Type = ? LIMIT 1");
            $stmt->bind_param("s", $property_type);
            $stmt->execute();
            $stmt->bind_result($property_id);
            $stmt->fetch();
            $stmt->close();

            if ($property_id) {
                // Prepare and bind
                $stmt = $conn->prepare("INSERT INTO reviews (property_id, rating, comment) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $property_id, $rating, $comment);

                // Execute the query
                echo '<div class="message">';
                if ($stmt->execute()) {
                    echo "New review added successfully";
                } else {
                    echo "Error: " . $stmt->error;
                }
                echo '</div>';

                // Close connection
                $stmt->close();
            } else {
                echo '<div class="message">Error: Invalid Property Type selected</div>';
            }

            $conn->close();
        }
        ?>
    </div>
</body>
</html>
