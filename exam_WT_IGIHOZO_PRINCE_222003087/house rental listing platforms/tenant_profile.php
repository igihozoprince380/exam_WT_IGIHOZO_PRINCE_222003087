<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hrlp";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for profile creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $photo = $_POST['photo'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    $sql = "INSERT INTO profile (photo, username, email, password, first_name, last_name)
    VALUES ('$photo', '$username', '$email', '$password', '$first_name', '$last_name')";

    if ($conn->query($sql) === TRUE) {
        echo "New tenant profile created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle delete action for profiles
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM profile WHERE id = $delete_id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Profile deleted successfully";
    } else {
        echo "Error deleting profile: " . $conn->error;
    }
}

// Query to retrieve tenant profiles
$sql = "SELECT * FROM profile";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Tenant Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            color: #333;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background: #5cb85c;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #4cae4c;
        }
        .profile {
            background: #fff;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .profile img {
            max-width: 100px;
            border-radius: 50%;
        }
        .profile p {
            margin: 5px 0;
        }
        .profile a {
            color: #d9534f;
            text-decoration: none;
        }
        .profile a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Create Tenant Profile</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Photo URL: <input type="text" name="photo"><br>
        Username: <input type="text" name="username" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        First Name: <input type="text" name="first_name"><br>
        Last Name: <input type="text" name="last_name"><br>
        <input type="submit" value="Submit">
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<h2>Tenant Profiles</h2>";
        while($row = $result->fetch_assoc()) {
            echo "<div class='profile'>";
            echo "<p>ID: " . $row["id"] . "</p>";
            echo "<p>Username: " . $row["username"] . "</p>";
            echo "<p>Email: " . $row["email"] . "</p>";
            echo "<p>First Name: " . $row["first_name"] . "</p>";
            echo "<p>Last Name: " . $row["last_name"] . "</p>";
            echo "<p>Created At: " . $row["created_at"] . "</p>";
            echo "<img src='" . $row["photo"] . "' alt='Profile Photo'>";
            echo "<br>";
            echo "<a href='?delete_id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this profile?\")'>Delete</a>";
            echo "</div>";
        }
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>
</body>
</html>
