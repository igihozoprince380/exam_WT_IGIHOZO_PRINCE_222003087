<?php
// Database credentials
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'hrlp';

// Attempt to connect to MySQL database
$mysqli = new mysqli($host, $user, $password, $database);

// Check connection
if ($mysqli->connect_errno) {
    die('Failed to connect to MySQL: ' . $mysqli->connect_error);
}
?>
