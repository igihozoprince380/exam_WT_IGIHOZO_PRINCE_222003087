<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hrlp";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete action for rental listings
if (isset($_GET['delete_rental_id'])) {
    // Sanitize and validate input
    $delete_id = intval($_GET['delete_rental_id']);  // Ensure $delete_id is an integer

    // Prepare delete statement
    $delete_sql = "DELETE FROM rental_listings WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);

    if ($stmt) {
        // Bind parameter
        $stmt->bind_param("i", $delete_id);

        // Execute delete query
        $stmt->execute();

        // Check for successful deletion
        if ($stmt->affected_rows > 0) {
            // Redirect after successful deletion
            header("Location: view_rental_listings.php");
            exit();
        } else {
            echo "Error deleting rental listing: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Prepare statement error: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

// Close connection
$conn->close();
?>
