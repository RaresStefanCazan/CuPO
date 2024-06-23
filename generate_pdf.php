<?php
ob_start(); // Start output buffering

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
function fetchUserData($email, $conn_users) {
    $sql = "SELECT * FROM users WHERE user = ?";
    $stmt = $conn_users->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: (" . $conn_users->errno . ") " . $conn_users->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    return $result->fetch_assoc();
}

// Function to calculate BMI
function calculateBMI($weight_kg, $height_cm) {
    if ($height_cm > 0) {
        $height_m = $height_cm / 100;
        $bmi = $weight_kg / ($height_m * $height_m);
        return $bmi;
    } else {
        return null;
    }
}

// Function to interpret BMI category
function interpretBMI($bmi) {
    if ($bmi === null) {
        return 'Unknown';
    } else if ($bmi < 18.5) {
        return 'Underweight';
    } else if ($bmi < 24.9) {
        return 'Normal weight';
    } else if ($bmi < 29.9) {
        return 'Overweight';
    } else {
        return 'Obese';
    }
}

// Function to calculate ideal weight range
function calculateIdealWeightRange($height_cm, $bmiCategory) {
    switch ($bmiCategory) {
        case 'Underweight':
            $idealWeight = 18.5 * ($height_cm * $height_cm / 10000);
            break;
        case 'Normal weight':
            $idealWeight = 24.9 * ($height_cm * $height_cm / 10000);
            break;
        case 'Overweight':
            $idealWeight = 29.9 * ($height_cm * $height_cm / 10000);
            break;
        case 'Obese':
            $idealWeight = 29.9 * ($height_cm * $height_cm / 10000);
            break;
        default:
            $idealWeight = null;
            break;
    }
    if ($idealWeight !== null) {
        $minIdealWeight = $idealWeight - 2;
        $maxIdealWeight = $idealWeight + 2;
        return [
            'min' => $minIdealWeight,
            'max' => $maxIdealWeight,
        ];
    } else {
        return null;
    }
}

// Function to fetch recommended foods
function fetchRecommendedFoods($conn_foods, $goal, $calorie_range) {
    $sql = "SELECT * FROM foods WHERE calories BETWEEN ? AND ? ORDER BY calories LIMIT 10";
    $stmt = $conn_foods->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: (" . $conn_foods->errno . ") " . $conn_foods->error);
    }
    $stmt->bind_param("ii", $calorie_range['min'], $calorie_range['max']);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get email from query string
$email = $_GET['email'] ?? '';
$email = urldecode($email); // Decode email parameter

// Fetch user data
$userData = fetchUserData($email, $conn_users);

// Calculate BMI
$weight_kg = $userData['weight_kg'];
$height_cm = $userData['height_cm'];
$bmi = calculateBMI($weight_kg, $height_cm);

// Interpret BMI category
$bmiCategory = interpretBMI($bmi);

// Calculate ideal weight range based on BMI category
$idealWeightRange = calculateIdealWeightRange($height_cm, $bmiCategory);

// Determine goal and set calorie ranges
$currentWeight = $weight_kg;
$idealWeight = $idealWeightRange['min']; // Taking the minimum ideal weight for simplicity
$goal = '';
$calorie_range = ['min' => 0, 'max' => 0];
if ($currentWeight < $idealWeight) {
    $weightDifference = $idealWeight - $currentWeight;
    $weightDifferenceText = "You should gain approximately {$weightDifference} kg to reach your ideal weight.";
    $goal = 'gain';
    $calorie_range = ['min' => 300, 'max' => 500]; // Example range for gaining weight
} elseif ($currentWeight > $idealWeight) {
    $weightDifference = $currentWeight - $idealWeight;
    $weightDifferenceText = "You should lose approximately {$weightDifference} kg to reach your ideal weight.";
    $goal = 'lose';
    $calorie_range = ['min' => 100, 'max' => 300]; // Example range for losing weight
} else {
    $weightDifferenceText = "You are at your ideal weight.";
}

// Fetch recommended foods based on BMI category and goal
$recommendedFoods = [];
if (!empty($goal)) {
    $recommendedFoods = fetchRecommendedFoods($conn_foods, $goal, $calorie_range);
}

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

// Output user information
$pdf->Cell(0, 10, 'User Information', 0, 1, 'C');
$pdf->Ln(5); // Add a line break

$pdf->Cell(0, 10, "Username: {$userData['user']}", 0, 1);
$pdf->Cell(0, 10, "Weight: {$userData['weight_kg']} kg", 0, 1);
$pdf->Cell(0, 10, "Height: {$userData['height_cm']} cm", 0, 1);
$pdf->Cell(0, 10, "BMI: " . ($bmi !== null ? number_format($bmi, 1) : 'Unknown'), 0, 1);
$pdf->Cell(0, 10, "BMI Category: {$bmiCategory}", 0, 1);
$pdf->Cell(0, 10, $weightDifferenceText, 0, 1);

$pdf->Ln(10); // Add a line break

// Output recommended foods based on BMI category and goal
if (!empty($goal)) {
    $pdf->Cell(0, 10, "Recommended Foods to {$goal} weight:", 0, 1, 'C');
    $pdf->Ln(5); // Add a line break
    
    // Set table headers
    $pdf->Cell(50, 10, 'Food Name', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Calories', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Protein (g)', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Fiber (g)', 1, 1, 'C');
    
    // Output food data
    if (empty($recommendedFoods)) {
        $pdf->Cell(0, 10, 'No recommended foods found.', 0, 1, 'C');
    } else {
        foreach ($recommendedFoods as $food) {
            $pdf->Cell(50, 10, $food['aliment'], 1);
            $pdf->Cell(30, 10, $food['calories'], 1);
            $pdf->Cell(30, 10, $food['protein'], 1);
            $pdf->Cell(30, 10, $food['fiber'], 1, 1);
        }
    }
}

// Clean and close output buffering
ob_end_clean();

// Send PDF file to the browser
$pdfFileName = 'user_food_information.pdf';
$pdf->Output($pdfFileName, 'D'); // 'D' forces download as attachment
?>
