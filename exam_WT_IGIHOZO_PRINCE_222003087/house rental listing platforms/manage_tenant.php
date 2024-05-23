<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register New Tenant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type=text], input[type=email], input[type=password], input[type=tel] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Register New Tenant</h2>
    
    <?php
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hrlp"; 
    
    try {
        // Create connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if all required fields are filled
            if(isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['name'])) {
                // Data from form
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashed password
                $name = $_POST['name'];
                $phone = $_POST['phone'] ?? null; // Optional field
                $address = $_POST['address'] ?? null; // Optional field
                
                // Start transaction
                $conn->beginTransaction();
                
                // Insert into users table
                $stmt_users = $conn->prepare("INSERT INTO users (Username, Email, Password_Hash, Role) VALUES (?, ?, ?, 'Tenant')");
                $stmt_users->execute([$username, $email, $password]);
                $user_id = $conn->lastInsertId(); // Retrieve the last inserted user ID
                
                // Insert into tenants table
                $stmt_tenants = $conn->prepare("INSERT INTO tenants (id, name, email, phone, address, password) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt_tenants->execute([$user_id, $name, $email, $phone, $address, $password]);
                
                // Commit the transaction
                $conn->commit();
                
                echo "<p>New tenant registered successfully.</p>";
                
            } else {
                echo "<p>Fill out all required fields to register a new tenant.</p>";
            }
            
        } else {
            echo "<p>Fill out the form to register a new tenant.</p>";
        }
        
    } catch (PDOException $e) {
        // Rollback on error
        if ($conn) {
            $conn->rollback();
        }
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    ?>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
    
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
    
        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone">
    
        <label for="address">Address:</label>
        <input type="text" id="address" name="address">
    
        <input type="submit" value="Register">
    </form>
</body>
</html>
