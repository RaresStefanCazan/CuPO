<?php

ob_start();

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
$goal = '';
$calorie_range = ['min' => 0, 'max' => 0];
if ($currentWeight < $idealWeight) {
    $weightDifference = $idealWeight - $currentWeight;
    $weightDifferenceText = "You should gain approximately {$weightDifference} kg to reach your ideal weight.";
    $goal = 'gain';
    $calorie_range = ['min' => 300, 'max' => 500]; 
} elseif ($currentWeight > $idealWeight) {
    $weightDifference = $currentWeight - $idealWeight;
    $weightDifferenceText = "You should lose approximately {$weightDifference} kg to reach your ideal weight.";
    $goal = 'lose';
    $calorie_range = ['min' => 100, 'max' => 300]; 
} else {
    $weightDifferenceText = "You are at your ideal weight.";
}


$recommendedFoods = [];
if (!empty($goal)) {
    $recommendedFoods = fetchRecommendedFoods($conn_foods, $goal, $calorie_range);
}


$conn_users->close();
$conn_foods->close();


header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=user_food_information.csv');
$output = fopen('php://output', 'w');

fputcsv($output, ['User Information']);
fputcsv($output, ["Username: {$userData['user']}"]);
fputcsv($output, ["Weight: {$userData['weight_kg']} kg"]);
fputcsv($output, ["Height: {$userData['height_cm']} cm"]);
fputcsv($output, ["BMI: " . ($bmi !== null ? number_format($bmi, 1) : 'Unknown')]);
fputcsv($output, ["BMI Category: {$bmiCategory}"]);
fputcsv($output, [$weightDifferenceText]);
fputcsv($output, ['']);


if (!empty($goal)) {
    fputcsv($output, ["Recommended Foods to {$goal} weight:"]);
    fputcsv($output, ['Food Name', 'Calories', 'Protein (g)', 'Fiber (g)']);
    if (empty($recommendedFoods)) {
        fputcsv($output, ['No recommended foods found.']);
    } else {
        foreach ($recommendedFoods as $food) {
            fputcsv($output, [$food['aliment'], $food['calories'], $food['protein'], $food['fiber']]);
        }
    }
}


fclose($output);
?>
