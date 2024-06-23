<?php
require_once('TCPDF/tcpdf.php'); // Adjust path to TCPDF library

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname_users = "cupo_users";
$dbname_foods = "cupo_users";

// Attempt connection to users database
$conn_users = new mysqli($servername, $username, $password, $dbname_users);
if ($conn_users->connect_error) {
    die("Connection to users database failed: " . $conn_users->connect_error);
}

// Attempt connection to foods database
$conn_foods = new mysqli($servername, $username, $password, $dbname_foods);
if ($conn_foods->connect_error) {
    die("Connection to foods database failed: " . $conn_foods->connect_error);
}

// Function to fetch user data
function fetchUserData($conn_users) {
    $sql = "SELECT * FROM users LIMIT 5"; // Fetching first 5 users
    $result = $conn_users->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to fetch food data
function fetchFoodData($conn_foods) {
    $sql = "SELECT * FROM foods LIMIT 5"; // Fetching first 5 foods
    $result = $conn_foods->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch user data
$usersData = fetchUserData($conn_users);

// Fetch food data
$foodsData = fetchFoodData($conn_foods);

// Close connections
$conn_users->close();
$conn_foods->close();

// Create new PDF instance
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Your Name');
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('User and Food Information');
$pdf->SetSubject('User and Food Statistics');
$pdf->SetKeywords('TCPDF, PDF, example, sample');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Output user data
$pdf->Cell(0, 10, 'User Data:', 0, 1);
foreach ($usersData as $user) {
    $pdf->Cell(0, 10, "ID: " . $user["id"] . " - Username: " . $user["username"], 0, 1);
}

// Output food data
$pdf->Cell(0, 10, 'Food Data:', 0, 1);
foreach ($foodsData as $food) {
    $pdf->Cell(0, 10, "ID: " . $food["id"] . " - Food Name: " . $food["aliment"], 0, 1);
}

// Close and output PDF document
$pdf->Output('user_food_information.pdf', 'D'); // 'D' forces download
?>
