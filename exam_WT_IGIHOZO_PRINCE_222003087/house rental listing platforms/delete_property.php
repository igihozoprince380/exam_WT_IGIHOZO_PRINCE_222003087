<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once 'db_connection.php';

// Check if the id parameter exists
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $id = trim($_GET['id']);

    // Prepare a delete statement
    $sql = 'DELETE FROM properties WHERE id = ?';

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            header('Location: view_properties.php');
            exit();
        } else {
            echo 'Oops! Something went wrong. Please try again later.';
        }
    }

    $stmt->close();
} else {
    header('Location: delete_property.php');
    exit();
}

$mysqli->close();
?>
