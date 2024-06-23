<?php
// Start output buffering
ob_start();

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

// Create CSV content
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=user_food_information.csv');
$output = fopen('php://output', 'w');

// Output user information
fputcsv($output, ['User Information']);
fputcsv($output, ["Username: {$userData['user']}"]);
fputcsv($output, ["Weight: {$userData['weight_kg']} kg"]);
fputcsv($output, ["Height: {$userData['height_cm']} cm"]);
fputcsv($output, ["BMI: " . ($bmi !== null ? number_format($bmi, 1) : 'Unknown')]);
fputcsv($output, ["BMI Category: {$bmiCategory}"]);
fputcsv($output, [$weightDifferenceText]);
fputcsv($output, ['']);

// Output recommended foods based on BMI category and goal
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

// Close the output
fclose($output);
?>
