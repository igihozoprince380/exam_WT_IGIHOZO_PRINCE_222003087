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

// Handle form submission for rental listing creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rental_submit'])) {
    $photo = $_POST['photo'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $location = $_POST['location'];

    $sql = "INSERT INTO rental_listings (photo, title, description, price, location)
    VALUES ('$photo', '$title', '$description', '$price', '$location')";

    if ($conn->query($sql) === TRUE) {
        echo "New rental listing created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle delete action for rental listings
if (isset($_GET['delete_rental_id'])) {
    $delete_id = $_GET['delete_rental_id'];
    $delete_sql = "DELETE FROM rental_listings WHERE id = $delete_id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Rental listing deleted successfully";
    } else {
        echo "Error deleting rental listing: " . $conn->error;
    }
}

// Query to retrieve rental listings
$sql = "SELECT * FROM rental_listings";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Rental Listing</title>
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
            margin-bottom: 20px;
        }
        input[type="text"], input[type="number"], textarea {
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
        .listing {
            background: #fff;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .listing img {
            max-width: 100px;
            border-radius: 4px;
        }
        .listing p {
            margin: 5px 0;
        }
        .listing a {
            color: #d9534f;
            text-decoration: none;
        }
        .listing a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Create Rental Listing</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Photo URL: <input type="text" name="photo"><br>
        Title: <input type="text" name="title" required><br>
        Description: <textarea name="description" required></textarea><br>
        Price: <input type="number" step="0.01" name="price" required><br>
        Location: <input type="text" name="location" required><br>
        <input type="submit" name="rental_submit" value="Submit">
    </form>

   
</body>
</html>
