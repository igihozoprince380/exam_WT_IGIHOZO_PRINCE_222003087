<?php
// Check if form is submitted with POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Retrieve POST data
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];

    // Example: Implement purchase logic (e.g., update quantity, process payment, etc.)
    // For example purposes, let's assume updating the quantity in the database
    $sql_update_quantity = "UPDATE furniture SET quantity = quantity - 1 WHERE id = $item_id";

    if ($conn->query($sql_update_quantity) === TRUE) {
        echo "<h2>Purchase Successful</h2>";
        echo "<p>Thank you for purchasing $item_name for $item_price.</p>";
        echo "<p>Your order has been processed.</p>";
    } else {
        echo "<h2>Error Processing Purchase</h2>";
        echo "<p>There was an error processing your purchase.</p>";
    }

    // Close connection
    $conn->close();
} else {
    // Redirect to purchase page if accessed directly without POST data (not expected)
    header("Location: purchase.php");
    exit();
}
?>
