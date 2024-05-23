<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once 'db_connection.php';

// Check if property ID is provided
if (isset($_GET['id'])) {
    $property_id = intval($_GET['id']);
    $username = $_SESSION['username'];

    // Update property booking status to 'booked' (assuming 'available' for simplicity)
    $sql = "UPDATE properties SET booking_status = 'booked', booked_by = ? WHERE id = ? AND booking_status = 'available'";
    $stmt = $mysqli->prepare($sql);

    // Check if prepare() succeeded
    if ($stmt === false) {
        die('MySQL prepare error: ' . $mysqli->error);
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("si", $username, $property_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $message = "Property booked successfully!";
        } else {
            $message = "Property is not available for booking.";
        }
    } else {
        $message = "Error executing query: " . $stmt->error;
    }

    $stmt->close();
} else {
    $message = "No property ID provided.";
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Property</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .message {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .message p {
            margin: 0;
        }

        .message a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="message">
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="my_listings.php">Back to my listing</a>
    </div>
</body>
</html>
