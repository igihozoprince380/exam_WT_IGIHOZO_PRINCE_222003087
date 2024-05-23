<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('10.JPEG');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 400px;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.9); 
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            color: #1877f2;
        }

        label {
            font-weight: bold;
            color: #050505;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #dddfe2;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        input[type="submit"] {
            background-color: #1877f2;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0e63af;
        }

        .agreement {
            margin-top: 10px;
            font-size: 14px;
            text-align: center;
            color: #606770;
        }

        .agreement a {
            color: #1877f2;
            text-decoration: none;
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #1877f2;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create an account</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username</label><br>
            <input type="text" id="username" name="username" required><br>

            <label for="email">Email address</label><br>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required><br>

            <label for="role">Role</label><br>
            <input type="radio" id="landlord" name="role" value="Landlord" required>
            <label for="landlord">Landlord</label>
            <input type="radio" id="tenant" name="role" value="Tenant" required>
            <label for="tenant">Tenant</label><br>

            <input type="submit" value="Sign Up">
       
        <a class="login-link" href="login.php">Already have an account? Log in</a>
    </div>

    <?php
    // PHP code for handling user registration
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Establish database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "hrlp";

        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve form data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $role = $_POST['role'];

        // Prepare SQL statement to insert user data into the users table
        $sql = "INSERT INTO users (Username, Email, Password_Hash, Role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $password, $role);

        // Execute SQL statement
        if ($stmt->execute()) {
            echo "<script>alert('User registered successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "\\n" . $conn->error . "');</script>";
        }

        // Close statement and database connection
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
