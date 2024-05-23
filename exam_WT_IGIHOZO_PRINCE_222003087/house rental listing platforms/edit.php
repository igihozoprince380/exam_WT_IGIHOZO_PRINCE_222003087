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

// Fetch the profile to be edited
$profile = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM profile WHERE id='$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $profile = $result->fetch_assoc();
    } else {
        echo "Profile not found";
        exit;
    }
}

// Handle form submission for update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $photo = $_POST['photo'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    $sql = "UPDATE profile SET photo='$photo', username='$username', email='$email', first_name='$first_name', last_name='$last_name'";
    if ($password) {
        $sql .= ", password='$password'";
    }
    $sql .= " WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Profile updated successfully";
        header("Location: landlord_profiles_view.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Landlord Profile</title>
</head>
<body>
    <h2>Edit Landlord Profile</h2>
    <?php if ($profile): ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="hidden" name="id" value="<?php echo $profile['id']; ?>">
            Photo URL: <input type="text" name="photo" value="<?php echo $profile['photo']; ?>"><br>
            Username: <input type="text" name="username" value="<?php echo $profile['username']; ?>" required><br>
            Email: <input type="email" name="email" value="<?php echo $profile['email']; ?>" required><br>
            Password: <input type="password" name="password"><br>
            <small>Leave blank if you do not want to change the password.</small><br>
            First Name: <input type="text" name="first_name" value="<?php echo $profile['first_name']; ?>"><br>
            Last Name: <input type="text" name="last_name" value="<?php echo $profile['last_name']; ?>"><br>
            <input type="submit" value="Update">
        </form>
    <?php else: ?>
        <p>Profile not found.</p>
    <?php endif; ?>
</body>
</html>
