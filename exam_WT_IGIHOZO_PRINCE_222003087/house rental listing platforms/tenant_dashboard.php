<?php
// index.php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard</title>
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

        #listing-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .listing {
            border: 1px solid #ddd;
            border-radius: 4px;
            margin: 1em 0;
            padding: 1em;
            width: 30%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
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
        <h1>Tenant Dashboard</h1>
    </header>
    <nav>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="my_listings.php">My Listings</a></li>
            <li><a href="message.php">Messages</a></li>
            <li><a href="tenant_profile.php">Profile</a></li>
            <li><a href="payment.php">Payment</a></li>
            <li><a href="view_promotion.php">Promotion</a></li>
             <li><a href="add_review.php"> add review</a></li>
             <li><a href="view_reviews.php"> view reviews</a></li>
             <li><a href="view_furniture.php"> view furniture</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <section id="welcome">
            <h2>Welcome, <?php echo htmlspecialchars($username); ?></h2>
            <p>Here are your recent activities:</p>
        </section>
        <section id="listings">
            <h3>Your Listings</h3>
            <div id="listing-container">
                <!-- Listings will be loaded here via JavaScript -->
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 house Rental listing Platform</p>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('fetch_listings.php')
                .then(response => response.json())
                .then(data => {
                    const listingContainer = document.getElementById('listing-container');

                    data.forEach(listing => {
                        const listingDiv = document.createElement('div');
                        listingDiv.classList.add('listing');

                        listingDiv.innerHTML = `
                            <h4>${listing.Title}</h4>
                            <p>${listing.Description}</p>
                            <p><strong>$${listing.Price.toFixed(2)}</strong></p>
                        `;

                        listingContainer.appendChild(listingDiv);
                    });
                })
                .catch(error => console.error('Error fetching listings:', error));
        });
    </script>
</body>
</html>
