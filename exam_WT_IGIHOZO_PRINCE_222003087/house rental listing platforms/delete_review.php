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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $review_id = intval($_POST["review_id"]);

    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $review_id);

    if ($stmt->execute()) {
        echo "Review deleted successfully.";
    } else {
        echo "Error deleting review: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();

// Redirect back to the reviews page
header("Location: view_reviews.php");
exit();
?>
