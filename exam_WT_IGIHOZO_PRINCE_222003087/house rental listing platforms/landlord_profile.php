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

// Handle form submission for create and update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $photo = $_POST['photo'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    if ($id) {
        // Update existing profile
        $sql = "UPDATE profile SET photo='$photo', username='$username', email='$email', password='$password', first_name='$first_name', last_name='$last_name' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Profile updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Create new profile
        $sql = "INSERT INTO profile (photo, username, email, password, first_name, last_name)
        VALUES ('$photo', '$username', '$email', '$password', '$first_name', '$last_name')";
        if ($conn->query($sql) === TRUE) {
            echo "New landlord profile created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Handle delete
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM profile WHERE id='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Profile deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch landlord profiles from the database
$sql = "SELECT * FROM profile";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Landlord Profiles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form input[type="text"], form input[type="email"], form input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        form input[type="submit"]:hover {
            background-color: #45a049;
        }
        .profile-card {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin: 20px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            vertical-align: top;
            background-color: #fff;
        }
        .profile-photo {
            width: 100%;
            border-radius: 10px;
        }
        .profile-info {
            margin-top: 15px;
        }
        .profile-info h3 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .profile-info p {
            margin: 5px 0;
            color: #555;
        }
        .profile-actions {
            margin-top: 15px;
        }
        .profile-actions button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .profile-actions button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <h2>Create Landlord Profile</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="hidden" name="id" id="profile-id">
        Photo URL: <input type="text" name="photo" id="profile-photo"><br>
        Username: <input type="text" name="username" id="profile-username" required><br>
        Email: <input type="email" name="email" id="profile-email" required><br>
        Password: <input type="password" name="password" id="profile-password" required><br>
        First Name: <input type="text" name="first_name" id="profile-first-name"><br>
        Last Name: <input type="text" name="last_name" id="profile-last-name"><br>
        <input type="submit" value="Submit">
    </form>

    <h2>Landlord Profiles</h2>
    <?php
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div class='profile-card'>";
            echo "<img class='profile-photo' src='" . $row["photo"] . "' alt='Profile Photo'>";
            echo "<div class='profile-info'>";
            echo "<h3>" . $row["username"] . "</h3>";
            echo "<p><strong>Email:</strong> " . $row["email"] . "</p>";
            echo "<p><strong>First Name:</strong> " . $row["first_name"] . "</p>";
            echo "<p><strong>Last Name:</strong> " . $row["last_name"] . "</p>";
            echo "<p><strong>Created At:</strong> " . $row["created_at"] . "</p>";
            echo "</div>";
            echo "<div class='profile-actions'>";
            echo "<a href='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?delete_id=" . $row["id"] . "'><button>Delete</button></a>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No profiles found</p>";
    }
    $conn->close();
    ?>
</body>
</html>
