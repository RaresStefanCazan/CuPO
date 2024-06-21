<?php
$servername = "localhost";  // Typically "localhost" for XAMPP
$username = "anea";  // Your database username
$password = "anea";  // Your database password
$dbname = "users";  // The name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>