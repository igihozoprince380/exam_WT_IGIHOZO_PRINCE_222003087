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

// Query to retrieve rental listings
$sql = "SELECT * FROM rental_listings";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome Page</title>
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: Arial, sans-serif;
      scroll-behavior: smooth;
      background-color: blue; 
    }

    .welcome-container {
      display: flex;
      flex-direction: column;
      height: 100%;
      justify-content: space-between;
    }

    .cover-picture {
      background-image: url('');
      background-size: cover;
      background-position: center;
      height: calc(100vh - 60px);
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      background-color: rgba(0, 0, 0, 0.7); 
      color: white;
    }

    .logo {
      height: 50px;
    }

    .header-buttons {
      display: flex;
      justify-content: flex-end;
      align-items: center;
    }

    .header button {
      padding: 10px 20px;
      font-size: 18px;
      cursor: pointer;
      margin-left: 10px;
      border: none;
      background-color: #3498db;
      color: white;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    .header button a {
      text-decoration: none;
      color: white;
    }

    .header button:hover {
      background-color: #2980b9;
    }

    .welcome-content {
      text-align: center;
      padding: 50px;
      background-color: #2c3e50; 
      color: skyblue;
    }

    .footer {
      background-color: #333; 
      color: white;
      padding: 20px;
      text-align: center;
    }

    .footer a {
      color: #3498db;
      text-decoration: none;
      margin: 0 10px;
    }

    .footer a:hover {
      text-decoration: underline;
    }

    .about, .contact {
      padding: 40px 20px;
      text-align: center;
    }

    .about {
      background-color: #f9f9f9;
    }

    .contact {
      background-color: #e9e9e9;
    }

    .content-button {
      padding: 10px 20px;
      font-size: 18px;
      cursor: pointer;
      margin: 10px;
      border: none;
      background-color: #3498db;
      color: white;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    .content-button:hover {
      background-color: #2980b9;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
      background-color: white; 
    }

    .main-section {
      text-align: center;
    }

    .featured-listings {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
    }

    .listing {
      border: 1px solid #ccc;
      padding: 10px;
      margin: 10px;
      width: 30%;
      box-shadow: 2px 2px 12px #aaa;
      background-color: skyblue; 
    }

    .listing img {
      width: 100%;
      height: auto;
    }

    .listing h3 {
      font-size: 1.5em;
    }

    .listing p {
      font-size: 1em;
      color: #555;
    }
  </style>
</head>
<body>
  <div class="welcome-container">
    <div class="header">
      <img src="100.svg" alt="Logo" class="logo">
      <div class="header-buttons">
        <button><a href="./about.html">About Us</a></button>
        <button><a href="./contact.html">Contact Us</a></button>
        <button onclick="redirectToLogin()">Sign In</button>
        <button onclick="redirectToRegister()">Sign Up</button>
      </div>
    </div>
    <div class="cover-picture"></div>
    <div class="welcome-content">
      <marquee>WELCOME TO OUR HOUSE RENTAL LISTING PLATFORM</marquee>    
    </div>

    <div class="container">
      <h2>Rental Listings</h2>

      <div class="featured-listings">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='listing'>";
                echo "<h3>" . (isset($row["title"]) ? $row["title"] : "N/A") . "</h3>";
                echo "<p>Description: " . (isset($row["description"]) ? $row["description"] : "N/A") . "</p>";
                echo "<p>Price: $" . (isset($row["price"]) ? $row["price"] : "N/A") . "</p>";
                echo "<p>Location: " . (isset($row["location"]) ? $row["location"] : "N/A") . "</p>";
                echo "<p>Created At: " . (isset($row["created_at"]) ? $row["created_at"] : "N/A") . "</p>";
                echo "<img src='" . (isset($row["photo"]) ? $row["photo"] : "") . "' alt='Rental Photo'>";
                echo "<br>";
                echo "</div>";
            }
        } else {
            echo "<p>No rental listings found.</p>";
        }

        $conn->close();
        ?>
      </div>
    </div>
    
    <div class="footer">
      <p>&copy; 2024 House Rental Listing Platform. All rights reserved.</p>
      <p>
        <a href="#">Privacy Policy</a> |
        <a href="#">Terms of Service</a>
      </p>
    </div>
  </div>
  <script>
    function redirectToLogin() {
      window.location.href = './login.php';
    }

    function redirectToRegister() {
      window.location.href = './register.php';
    }

    function scrollToSection(sectionId) {
      document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
    }
  </script>
</body>
</html>
