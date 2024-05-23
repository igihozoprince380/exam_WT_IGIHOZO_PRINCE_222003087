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
function insertPayment($conn, $property_id, $user_name, $amount, $payment_date, $payment_method, $payment_status) {
    $stmt = $conn->prepare("INSERT INTO payments (property_id, user_name, amount, payment_date, payment_method, payment_status) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param("isdsss", $property_id, $user_name, $amount, $payment_date, $payment_method, $payment_status);

    if ($stmt->execute()) {
        echo "New payment record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $property_id = sanitizeInput($_POST["property_id"]);
    $user_name = sanitizeInput($_POST["user_name"]);
    $amount = sanitizeInput($_POST["amount"]);
    $payment_date = sanitizeInput($_POST["payment_date"]);
    $payment_method = sanitizeInput($_POST["payment_method"]);
    $payment_status = "Pending"; // Automatically set the payment status to "Pending"

    insertPayment($mysqli, $property_id, $user_name, $amount, $payment_date, $payment_method, $payment_status);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Payment</title>
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

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form input[type="text"],
        form input[type="date"],
        form select,
        form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #333;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #555;
        }

        .payment-details {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
    </style>
    <script>
        function showPaymentDetails() {
            const paymentMethod = document.querySelector('select[name="payment_method"]').value;
            let message = '';

            switch (paymentMethod) {
                case 'Mobile Money':
                    message = 'Mobile Money: 23456 registered to Igihozo Prince';
                    break;
                case 'Bank':
                    message = 'Bank (BK): 23435678987 registered to Igihozo Prince';
                    break;
                case 'Airtel Money':
                    message = 'Airtel Money: 23412 registered to Igihozo Prince';
                    break;
                default:
                    message = '';
            }

            document.getElementById('payment-details').innerText = message;
        }

        function fetchPropertyDetails() {
            const propertyId = document.querySelector('select[name="property_id"]').value;
            if (propertyId) {
                fetch('get_property_details.php?property_id=' + propertyId)
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('input[name="amount"]').value = data.price;
                        document.querySelector('textarea[name="description"]').value = data.description;
                    });
            } else {
                document.querySelector('input[name="amount"]').value = '';
                document.querySelector('textarea[name="description"]').value = '';
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>Insert Payment</h1>
    </header>
    
    <main>
        <form action="payment.php" method="post">
            Property:<br>
            <select name="property_id" onchange="fetchPropertyDetails()" required>
                <option value="">Select Property</option>
                <?php
                // Fetch properties from the database
                $result = $mysqli->query("SELECT id, Property_Type, Address FROM properties");
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['Property_Type']) . ' - ' . htmlspecialchars($row['Address']) . '</option>';
                }
                ?>
            </select><br>
            User Name:<br>
            <input type="text" name="user_name" required><br>
            Amount:<br>
            <input type="number" name="amount" required><br>
            Description:<br>
            <textarea name="description"></textarea><br>
            Payment Date:<br>
            <input type="date" name="payment_date" required><br>
            Payment Method:<br>
            <select name="payment_method" onchange="showPaymentDetails()" required>
                <option value="">Select Method</option>
                <option value="Mobile Money">Mobile Money</option>
                <option value="Bank">Bank</option>
                <option value="Airtel Money">Airtel Money</option>
            </select><br>
            <input type="hidden" name="payment_status" value="Pending"><br><br>
            <input type="submit" value="Submit">
        </form>

        <div id="payment-details" class="payment-details"></div>
    </main>
</body>
</html>
