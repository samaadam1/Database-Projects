<?php
// Database connection settings
$servername = "localhost";  // Database host (usually localhost)
$username = "root";         // Database username (default in XAMPP/WAMP is root)
$password = "";             // Database password (default in XAMPP/WAMP is an empty string)
$dbname = "CarRentalSystem";  // The name of your database (use the name you created for your project)

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
