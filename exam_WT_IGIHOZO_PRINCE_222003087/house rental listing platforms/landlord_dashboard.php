<?php
session_start();

// Check if the username is set in the session
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the username is not set
    header('Location: login.php');
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 1em 0;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
        }

        .logo {
            height: 50px;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            background-color: #333;
            overflow: hidden;
        }

        nav li {
            float: left;
        }

        nav li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        nav li a:hover {
            background-color: #111;
        }

        main {
            padding: 1em;
            margin: 1em;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #properties {
            margin-top: 20px;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1em 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <img src="100.svg" alt="Logo" class="logo">
        <h1>Landlord Dashboard</h1>
    </header>
    <nav>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="view_Properties.php">view Properties</a></li>
            <li><a href="Add_Property.php">Add Property</a></li>
            <li><a href="Messages.php">Messages</a></li>
            <li><a href="Landlord_profile.php">Profile</a></li>
            <li><a href="view_payment.php">view payment</a></li>
             <li><a href="Rental_listings.php"> rental listings</a></li>
             <li><a href="view_Rental_listings.php">view rental listings</a></li>
             <li><a href="promotion.php">promotion</a></li>
              <li><a href="v_promotion.php">view promotion</a></li>
               <li><a href="review.php">review</a></li>
                <li><a href="manage_tenant.php">manage tenant</a></li>
 <li><a href="add_furniture.php">add furniture</a></li>
 <li><a href="furniture.php">furniture</a></li>

            <li><a href="Logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <section id="welcome">
            <h2>Welcome, <?php echo htmlspecialchars($username); ?></h2>
            <p>Here are your properties:</p>
        </section>
        <section id="properties">
            <!-- Properties will be loaded here -->
        </section>
    </main>
    <footer>
        <p>&copy; 2024 house Rental listing Platforms</p>
    </footer>
    <script src="scripts.js"></script>
</body>
</html>
