<?php
session_start();

// Check if the user is logged in and is a tenant
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Tenant') {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once 'db_connection.php';

// Retrieve tenant's User_ID and username based on logged-in username
$tenant_username = $_SESSION['username'];
$sql_select_tenant = "SELECT User_ID, Username FROM users WHERE Username = ?";
$stmt_select_tenant = $mysqli->prepare($sql_select_tenant);

if ($stmt_select_tenant) {
    $stmt_select_tenant->bind_param('s', $tenant_username);
    $stmt_select_tenant->execute();
    $stmt_select_tenant->store_result();

    if ($stmt_select_tenant->num_rows > 0) {
        $stmt_select_tenant->bind_result($tenant_id, $tenant_username);
        $stmt_select_tenant->fetch();

        // Retrieve distinct messages sent by landlords to this tenant
        $sql_select_messages = "SELECT DISTINCT m.subject, m.message, m.timestamp, u.Username AS sender_username, 
                                        (SELECT Username FROM users WHERE User_ID = m.recipient_id) AS recipient_username
                                FROM messages m 
                                INNER JOIN users u ON m.sender_id = u.User_ID 
                                WHERE m.recipient_id = ?";
        $stmt_select_messages = $mysqli->prepare($sql_select_messages);

        if ($stmt_select_messages) {
            $stmt_select_messages->bind_param('i', $tenant_id);
            $stmt_select_messages->execute();
            $result = $stmt_select_messages->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="message">';
                    echo '<h3>Subject: ' . htmlspecialchars($row['subject']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['message']) . '</p>';
                    echo '<p>Sent by Landlord: ' . htmlspecialchars($row['sender_username']) . '</p>';
                    echo '<p>Sent to Tenant: ' . htmlspecialchars($row['recipient_username']) . '</p>';
                    echo '<p>Sent on: ' . htmlspecialchars($row['timestamp']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No messages from landlords yet.</p>';
            }

            $stmt_select_messages->close();
        } else {
            echo 'Error preparing statement: ' . $mysqli->error;
        }
    } else {
        echo 'Tenant username not found.';
    }

    $stmt_select_tenant->close();
} else {
    echo 'Error preparing statement: ' . $mysqli->error;
}

$mysqli->close(); // Close the database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color:yellowgreen; 
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff; 
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }
        .messages {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            background-color:yellow; 
            color:darkgreen; 
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .message h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Messages</h1>
        
        <!-- Display Messages -->
        <div class="messages">
            <h2>Messages from Landlords to <?php echo htmlspecialchars($tenant_username); ?></h2>
            <?php
        
            ?>
        </div>
    </div>
</body>
</html>
