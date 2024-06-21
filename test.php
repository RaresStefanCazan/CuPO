<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Replace these with your actual database details
$servername = "localhost";
$dbname = "cupo_users";
$username = "root";
$password = "";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_COOKIE['user_email'])) {
    $username = urldecode($_COOKIE['user_email']); }

// Define the hardcoded username
$user_username = htmlspecialchars($username);

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT height, weight FROM users WHERE user = ?");
$stmt->bind_param("s", $user_username);

// Execute the statement
$stmt->execute();

// Bind the result variables
$stmt->bind_result($height, $weight);

// Fetch the data
if ($stmt->fetch()) {
    echo "Height: " . $height . " cm<br>";
    echo "Weight: " . $weight . " kg<br>";
} else {
    echo "No user found with username: " . $user_username;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
