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

// Retrieve reviews with property details
$sql = "
    SELECT r.id, r.rating, r.comment, p.Property_Type, p.Address, p.Description, p.Price, p.Photo
    FROM reviews r
    JOIN properties p ON r.property_id = p.id
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reviews</title>
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

        .review {
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 10px;
        }

        .review h2 {
            margin-top: 0;
        }

        .review img {
            max-width: 100%;
            height: auto;
        }

        .review .property-details {
            margin-bottom: 10px;
        }

        .review .property-details strong {
            display: block;
        }
    </style>
</head>
<body>
    <header>
        <h1>View Reviews</h1>
    </header>
    
    <main>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="review">';
                echo '<div class="property-details">';
                echo '<strong>Property Type:</strong> ' . htmlspecialchars($row['Property_Type']);
                echo '<strong>Address:</strong> ' . htmlspecialchars($row['Address']);
                echo '<strong>Description:</strong> ' . htmlspecialchars($row['Description']);
                echo '<strong>Price:</strong> $' . htmlspecialchars($row['Price']);
                if (!empty($row['Photo'])) {
                    echo '<img src="' . htmlspecialchars($row['Photo']) . '" alt="Property Photo">';
                }
                echo '</div>';
                echo '<p><strong>Rating:</strong> ' . htmlspecialchars($row['rating']) . '/5</p>';
                echo '<p><strong>Comment:</strong> ' . htmlspecialchars($row['comment']) . '</p>';
                
                // Add delete form
                echo '<form method="POST" action="delete_review.php">';
                echo '<input type="hidden" name="review_id" value="' . htmlspecialchars($row['id']) . '">';
                echo '<button type="submit">Delete</button>';
                echo '</form>';
                
                echo '</div>';
            }
        } else {
            echo '<p>No reviews found.</p>';
        }

        $conn->close();
        ?>
    </main>
</body>
</html>
