<?php
session_start();

// Check if the user is logged in and is an admin (or landlord)
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Landlord') {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once 'db_connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_username = $_POST['recipient_username'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Validate inputs (you may need more validation)
    if (empty($recipient_username) || empty($subject) || empty($message)) {
        echo 'Please fill in all fields.';
    } else {
        // Retrieve recipient's User_ID based on username
        $sql_select_user_id = "SELECT User_ID FROM users WHERE Username = ?";
        $stmt_select_user_id = $mysqli->prepare($sql_select_user_id);

        if ($stmt_select_user_id) {
            $stmt_select_user_id->bind_param('s', $recipient_username);
            $stmt_select_user_id->execute();
            $stmt_select_user_id->store_result();

            if ($stmt_select_user_id->num_rows > 0) {
                $stmt_select_user_id->bind_result($recipient_id);
                $stmt_select_user_id->fetch();

                // Insert message into database
                $sql_insert_message = 'INSERT INTO messages (sender_id, recipient_id, subject, message, timestamp) VALUES (?, ?, ?, ?, NOW())';
                $stmt_insert_message = $mysqli->prepare($sql_insert_message);
                
                if ($stmt_insert_message) {
                    $sender_id = $_SESSION['user_id']; // Assuming you store user ID in session
                    $stmt_insert_message->bind_param('iiss', $sender_id, $recipient_id, $subject, $message);
                    
                    if ($stmt_insert_message->execute()) {
                        echo 'Message sent successfully.';
                    } else {
                        echo 'Error sending message: ' . $stmt_insert_message->error;
                    }

                    $stmt_insert_message->close(); // Close the insert statement

                    // Retrieve and display messages sent by the admin (or landlord)
                    $sql = "SELECT m.subject, m.message, m.timestamp, u.Username as recipient_username 
                            FROM messages m 
                            INNER JOIN users u ON m.recipient_id = u.User_ID 
                            WHERE m.sender_id = ?";
                    $stmt_display_messages = $mysqli->prepare($sql);

                    if ($stmt_display_messages) {
                        $stmt_display_messages->bind_param('i', $sender_id);
                        $stmt_display_messages->execute();
                        $result = $stmt_display_messages->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="message">';
                                echo '<h3>Subject: ' . htmlspecialchars($row['subject']) . '</h3>';
                                echo '<p>' . htmlspecialchars($row['message']) . '</p>';
                                echo '<p>Sent to Username: ' . htmlspecialchars($row['recipient_username']) . '</p>';
                                echo '<p>Sent on: ' . htmlspecialchars($row['timestamp']) . '</p>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No messages sent yet.</p>';
                        }

                        $stmt_display_messages->close(); // Close the display messages statement
                    } else {
                        echo 'Error preparing statement: ' . $mysqli->error;
                    }
                } else {
                    echo 'Error preparing statement: ' . $mysqli->error;
                }
            } else {
                echo 'Recipient username not found.';
            }

            $stmt_select_user_id->close();
        } else {
            echo 'Error preparing statement: ' . $mysqli->error;
        }
    }
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
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        form {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        form label, form textarea {
            display: block;
            margin-bottom: 10px;
        }
        form textarea {
            width: 100%;
            height: 100px;
            resize: vertical;
        }
        .messages {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #e9e9e9;
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
        
        <!-- Message Form -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="recipient_username">Recipient Username:</label>
            <input type="text" name="recipient_username" id="recipient_username" required>
            
            <label for="subject">Subject:</label>
            <input type="text" name="subject" id="subject" required>
            
            <label for="message">Message:</label>
            <textarea name="message" id="message" required></textarea>
            
            <button type="submit">Send Message</button>
        </form>
        
        <!-- Display Messages -->
        <div class="messages">
            <h2>Messages Sent</h2>
            <?php
            // Messages display code is already integrated above
            ?>
        </div>
    </div>
</body>
</html>
