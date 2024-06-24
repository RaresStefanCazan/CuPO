<?php
ob_start(); 

require_once('TCPDF/tcpdf.php'); 

$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname_users = "cupo_users";
$dbname_foods = "cupo_users";

$conn_users = new mysqli($servername, $username, $password, $dbname_users);
if ($conn_users->connect_error) {
    die("Connection to users database failed: " . $conn_users->connect_error);
}

$conn_foods = new mysqli($servername, $username, $password, $dbname_foods);
if ($conn_foods->connect_error) {
    die("Connection to foods database failed: " . $conn_foods->connect_error);
}

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

function calculateBMI($weight_kg, $height_cm) {
    if ($height_cm > 0) {
        $height_m = $height_cm / 100;
        $bmi = $weight_kg / ($height_m * $height_m);
        return $bmi;
    } else {
        return null;
    }
}

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

function calculateIdealWeightRange($height_cm, $bmiCategory) {
    switch ($bmiCategory) {
        case 'Underweight':
            $idealWeight = 18.5 * ($height_cm * $height_cm / 10000);
            break;
        case 'Normal weight':
            $idealWeight = 24.9 * ($height_cm * $height_cm / 10000);
            break;
        case 'Overweight':
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

$email = $_GET['email'] ?? '';
$email = urldecode($email); 

$userData = fetchUserData($email, $conn_users);

$weight_kg = $userData['weight_kg'];
$height_cm = $userData['height_cm'];
$bmi = calculateBMI($weight_kg, $height_cm);
$bmiCategory = interpretBMI($bmi);

$idealWeightRange = calculateIdealWeightRange($height_cm, $bmiCategory);

$currentWeight = $weight_kg;
$idealWeight = $idealWeightRange['min'];
$calorie_range = ['min' => 0, 'max' => 0];

if ($bmiCategory === 'Normal weight') {
    $goal = 'maintain';
    $weightDifferenceText = "You are at your ideal weight.";
} else {
    if ($currentWeight < $idealWeight) {
        $goal = 'gain';
        $weightDifference = $idealWeight - $currentWeight;
        $weightDifferenceText = "You should gain approximately {$weightDifference} kg to reach your ideal weight.";
        $calorie_range = ['min' => 300, 'max' => 500];
    } elseif ($currentWeight > $idealWeight) {
        $goal = 'lose';
        $weightDifference = $currentWeight - $idealWeight;
        $weightDifferenceText = "You should lose approximately {$weightDifference} kg to reach your ideal weight.";
        $calorie_range = ['min' => 95, 'max' => 300]; 
    } else {
        $goal = 'maintain';
        $weightDifferenceText = "You are at your ideal weight.";
    }
}

$recommendedFoods = [];
if ($bmiCategory === 'Normal weight') {
    $recommendedFoods = fetchRecommendedFoods($conn_foods, $goal, ['min' => 200, 'max' => 215]);
} elseif (!empty($goal)) {
    $recommendedFoods = fetchRecommendedFoods($conn_foods, $goal, $calorie_range);
}

$conn_users->close();
$conn_foods->close();

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator('Your Name');
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('User and Food Information');
$pdf->SetSubject('User and Food Statistics');
$pdf->SetKeywords('TCPDF, PDF, example, sample');

$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

$pdf->Cell(0, 10, 'User Information', 0, 1, 'C');
$pdf->Ln(5); 

$pdf->Cell(0, 10, "Username: {$userData['user']}", 0, 1);
$pdf->Cell(0, 10, "Weight: {$userData['weight_kg']} kg", 0, 1);
$pdf->Cell(0, 10, "Height: {$userData['height_cm']} cm", 0, 1);
$pdf->Cell(0, 10, "BMI: " . ($bmi !== null ? number_format($bmi, 1) : 'Unknown'), 0, 1);
$pdf->Cell(0, 10, "BMI Category: {$bmiCategory}", 0, 1);
$pdf->Cell(0, 10, $weightDifferenceText, 0, 1);

$pdf->Ln(10); 

if ($bmiCategory === 'Normal weight') {
    $pdf->Cell(0, 10, "Recommended Foods for Normal Weight (200-215 Calories):", 0, 1, 'C');
    $pdf->Ln(5); 
    
    $pdf->Cell(50, 10, 'Food Name', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Calories', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Protein (g)', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Fiber (g)', 1, 1, 'C');
    
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
} elseif (!empty($goal)) {
    $pdf->Cell(0, 10, "Recommended Foods to {$goal} weight:", 0, 1, 'C');
    $pdf->Ln(5); 
    
    $pdf->Cell(50, 10, 'Food Name', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Calories', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Protein (g)', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Fiber (g)', 1, 1, 'C');
    
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

$pdf->Output('user_food_information.pdf', 'D');
