<?php
require_once __DIR__ . '/../model/database.php'; // Include your database connection
require_once __DIR__ . '/../model/StatisticUserModel.php';

header('Content-Type: application/json');

try {
    // Check if the cookie is set
    if (isset($_COOKIE['user_email'])) {
        $username = urldecode($_COOKIE['user_email']);

        // Initialize StatisticUserModel and get statistics
        $userModel = new StatisticUserModel($conn); // Assuming $conn is your database connection
        $statistics = $userModel->getUserStatistics($username);

        // Calculate BMI and category if statistics are found
        if ($statistics) {
            $bmi = $userModel->calculateBMI($statistics['height_cm'], $statistics['weight_kg']);
            $bmiCategory = $userModel->interpretBMI($bmi);

            $statistics['bmi_category'] = $bmiCategory; // Add BMI category to the statistics array

            echo json_encode(['status' => 'success', 'data' => $statistics]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No statistics found for the user.']);
        }
    } else {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    }
} catch (Exception $e) {
    error_log("Server error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
