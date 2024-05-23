<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('3.JPEG'); 
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="email"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="" method="POST">
            <label for="email">Email address</label><br>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required><br>

            <input type="submit" value="Log In">
        </form>
        <?php
        session_start();
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
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Prepare SQL statement to retrieve user data by email
            $sql = "SELECT * FROM users WHERE Email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);

            // Execute SQL statement
            $stmt->execute();
            
            // Get result
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // User found, verify password
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['Password_Hash'])) {
                    // Password is correct, set session variables and redirect to respective dashboard
                    $_SESSION['user_id'] = $row['User_ID'];
                    $_SESSION['role'] = $row['Role'];
                    $_SESSION['username'] = $row['Username']; // Store the username in session
                    
                    if ($_SESSION['role'] === 'Landlord') {
                        header('Location: landlord_dashboard.php');
                        exit();
                    } elseif ($_SESSION['role'] === 'Tenant') {
                        header('Location: tenant_dashboard.php');
                        exit();
                    }
                } else {
                    echo "<p class='error'>Invalid email or password.</p>";
                }
            } else {
                echo "<p class='error'>User not found.</p>";
            }

            // Close statement and database connection
            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
