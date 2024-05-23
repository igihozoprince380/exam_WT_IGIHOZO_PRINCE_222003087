<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property</title>
    <style>
        /* CSS styles */
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

        nav {
            background-color: #444;
            padding: 10px;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin-right: 10px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
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
        <h1>Add Property</h1>
    </header>
   
    <main>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div>
                <label for="property_type">Property Type:</label>
                <select name="property_type" id="property_type">
                    <option value="">Select</option>
                    <option value="house">House</option>
                    <option value="apartment">Apartment</option>
                    <option value="condo">Condo</option>
                </select>
                <span class="error"><?php echo isset($property_type_err) ? $property_type_err : ''; ?></span>
            </div>
            <div>
                <label for="address">Address:</label>
                <input type="text" name="address" id="address">
                <span class="error"><?php echo isset($address_err) ? $address_err : ''; ?></span>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea name="description" id="description"></textarea>
            </div>
            <div>
                <label for="price_type">Price Type:</label>
                <select name="price_type" id="price_type">
                    <option value="buy">Buy</option>
                    <option value="rent">Rent</option>
                    <option value="booking">Booking</option>
                </select>
                <span class="error"><?php echo isset($price_type_err) ? $price_type_err : ''; ?></span>
            </div>
            <div>
                <label for="price">Price:</label>
                <input type="text" name="price" id="price">
                <span class="error"><?php echo isset($price_err) ? $price_err : ''; ?></span>
            </div>
            <div>
                <label for="photo">Photo:</label>
                <input type="text" name="photo" id="photo">
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

<?php
session_start();

// Define variables to hold form input values and error messages
$property_type = $address = $description = $price_type = $price = $photo = '';
$property_type_err = $address_err = $price_type_err = $price_err = '';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once 'db_connection.php';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Validate price type
    if (empty(trim($_POST['price_type']))) {
        $price_type_err = 'Please select a price type.';
    } else {
        $price_type = trim($_POST['price_type']);
    }

    // Validate price
    if (empty(trim($_POST['price']))) {
        $price_err = 'Please enter the property price.';
    } elseif (!is_numeric(trim($_POST['price']))) {
        $price_err = 'Price must be a numeric value.';
    } else {
        $price = trim($_POST['price']);
    }

    // Check input errors before inserting into database
    if (empty($property_type_err) && empty($address_err) && empty($price_type_err) && empty($price_err)) {
        // Prepare an insert statement
        $sql = 'INSERT INTO properties (Property_Type, Address, Description, Price_Type, Price, Photo) VALUES (?, ?, ?, ?, ?, ?)';
        
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('ssssss', $property_type, $address, $_POST['description'], $price_type, $price, $_POST['photo']);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to properties page after successful creation
                header('Location: add_property.php');
                exit();
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }

            // Close statement
            $stmt->close();
        }
    }
}

// Close connection
$mysqli->close();
?>
