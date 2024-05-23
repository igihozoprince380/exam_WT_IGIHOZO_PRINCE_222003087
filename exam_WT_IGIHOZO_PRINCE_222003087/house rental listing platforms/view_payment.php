<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once 'db_connection.php';

// Function to sanitize and validate input
function sanitizeInput($input) {
    global $mysqli;
    return htmlspecialchars(trim($mysqli->real_escape_string($input)));
}

// Function to insert a new payment
function insertPayment($conn, $user_name, $amount, $payment_date, $payment_method, $payment_status) {
    $stmt = $conn->prepare("INSERT INTO payments (user_name, amount, payment_date, payment_method, payment_status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $user_name, $amount, $payment_date, $payment_method, $payment_status);

    if ($stmt->execute()) {
        echo "New payment record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Retrieve payments from the database
$sql = 'SELECT * FROM payments';
$result = $mysqli->query($sql);

// Check if there are payments
if ($result === false) {
    echo 'Error executing query: ' . $mysqli->error;
} else {
    // Initialize variables
    $payments = [];

    // Fetch all payments into an array
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }

    // Close the result set
    $result->close();
}

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payments</title>
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

        .payment {
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 10px;
        }

        .payment h2 {
            margin-top: 0;
        }

        .action-select {
            margin-bottom: 10px;
        }

        .action-select select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            color: darkgreen;
            background-color: yellow;
        }

        .action-select select option {
            padding: 5px;
            color: darkgreen;
            background-color: yellow;
        }

        .action-select select option:hover {
            background-color: blue;
        }
    </style>
</head>
<body>
    <header>
        <h1>View Payments</h1>
    </header>
    
    <main>
       

        <?php
        // Check if action parameter is set and valid
        if (isset($_GET['action']) && in_array($_GET['action'], ['completed', 'pending', 'failed'])) {
            $action = $_GET['action'];

            // Filter payments based on action
            $filteredPayments = array_filter($payments, function($payment) use ($action) {
                return $payment['payment_status'] === $action;
            });

            // Display filtered payments
            if (!empty($filteredPayments)) {
                foreach ($filteredPayments as $payment) {
                    echo '<div class="payment">';
                    echo '<h2>Payment ID: ' . htmlspecialchars($payment['payment_id']) . '</h2>';
                    echo '<p><strong>User Name:</strong> ' . htmlspecialchars($payment['user_name']) . '</p>';
                    echo '<p><strong>Amount:</strong> $' . htmlspecialchars($payment['amount']) . '</p>';
                    echo '<p><strong>Date:</strong> ' . htmlspecialchars($payment['payment_date']) . '</p>';
                    echo '<p><strong>Method:</strong> ' . htmlspecialchars($payment['payment_method']) . '</p>';
                    echo '<p><strong>Status:</strong> ' . htmlspecialchars($payment['payment_status']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No payments found for ' . ucfirst($action) . ' status.</p>';
            }
        } else {
            // Display all payments initially
            if (!empty($payments)) {
                foreach ($payments as $payment) {
                    echo '<div class="payment">';
                    echo '<h2>Payment ID: ' . htmlspecialchars($payment['payment_id']) . '</h2>';
                    echo '<p><strong>User Name:</strong> ' . htmlspecialchars($payment['user_name']) . '</p>';
                    echo '<p><strong>Amount:</strong> $' . htmlspecialchars($payment['amount']) . '</p>';
                    echo '<p><strong>Date:</strong> ' . htmlspecialchars($payment['payment_date']) . '</p>';
                    echo '<p><strong>Method:</strong> ' . htmlspecialchars($payment['payment_method']) . '</p>';
                    echo '<p><strong>Status:</strong> ' . htmlspecialchars($payment['payment_status']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No payments found.</p>';
            }
        }
        ?>
    </main>
</body>
</html>
