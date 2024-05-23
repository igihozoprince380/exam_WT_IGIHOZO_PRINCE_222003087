<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once 'db_connection.php';

// Define variables and initialize with empty values
$property_type = $address = $description = $price = $photo = '';
$property_type_err = $address_err = $price_err = '';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Validate property type
    if (empty(trim($_POST['property_type']))) {
        $property_type_err = 'Please select a property type.';
    } else {
        $property_type = trim($_POST['property_type']);
    }

    // Validate address
    if (empty(trim($_POST['address']))) {
        $address_err = 'Please enter the property address.';
    } else {
        $address = trim($_POST['address']);
    }

    // Validate price
    if (empty(trim($_POST['price']))) {
        $price_err = 'Please enter the property price.';
    } elseif (!is_numeric(trim($_POST['price']))) {
        $price_err = 'Price must be a numeric value.';
    } else {
        $price = trim($_POST['price']);
    }

    // Check input errors before updating the database
    if (empty($property_type_err) && empty($address_err) && empty($price_err)) {
        $sql = 'UPDATE properties SET Property_Type=?, Address=?, Description=?, Price=?, Photo=? WHERE id=?';

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('sssssi', $property_type, $address, $_POST['description'], $price, $_POST['photo'], $id);

            if ($stmt->execute()) {
                header('Location: view_properties.php');
                exit();
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }

            $stmt->close();
        }
    }
} else {
    if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
        $id = trim($_GET['id']);

        $sql = 'SELECT * FROM properties WHERE id = ?';
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('i', $id);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $property_type = $row['Property_Type'];
                    $address = $row['Address'];
                    $description = $row['Description'];
                    $price = $row['Price'];
                    $photo = $row['Photo'];
                } else {
                    header('Location: edit_property.php');
                    exit();
                }
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }
        }
        $stmt->close();
    } else {
        header('Location: error.php');
        exit();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property</title>
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
            max-width: 500px;
            margin: auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Property</h1>
    </header>
   
    <main>
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div>
                <label for="property_type">Property Type:</label>
                <select name="property_type" id="property_type">
                    <option value="">Select</option>
                    <option value="house" <?php echo $property_type == 'house' ? 'selected' : ''; ?>>House</option>
                    <option value="apartment" <?php echo $property_type == 'apartment' ? 'selected' : ''; ?>>Apartment</option>
                    <option value="condo" <?php echo $property_type == 'condo' ? 'selected' : ''; ?>>Condo</option>
                </select>
                <span class="error"><?php echo isset($property_type_err) ? $property_type_err : ''; ?></span>
            </div>
            <div>
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" value="<?php echo $address; ?>">
                <span class="error"><?php echo isset($address_err) ? $address_err : ''; ?></span>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea name="description" id="description"><?php echo $description; ?></textarea>
            </div>
            <div>
                <label for="price">Price:</label>
                <input type="text" name="price" id="price" value="<?php echo $price; ?>">
                <span class="error"><?php echo isset($price_err) ? $price_err : ''; ?></span>
            </div>
            <div>
                <label for="photo">Photo:</label>
                <input type="text" name="photo" id="photo" value="<?php echo $photo; ?>">
            </div>
            <div>
                <input type="submit" value="Submit">
            </div>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Rental Platform</p>
    </footer>
</body>
</html>
