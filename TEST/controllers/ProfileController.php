<?php
require_once __DIR__ . '/../model/database.php'; // Include your database connection
require_once __DIR__ . '/../model/ProfileModel.php'; // Include ProfileModel

header('Content-Type: application/json');

try {
    $profileModel = new ProfileModel($conn); // Assuming $conn is your database connection

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_profile') {
        if (isset($_COOKIE['user_email'])) {
            $username = urldecode($_COOKIE['user_email']);
            $profile = $profileModel->getUserProfile($username);

            if ($profile) {
                echo json_encode(['status' => 'success', 'data' => $profile]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No profile found for the user.']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
        if (isset($_COOKIE['user_email'])) {
            $username = urldecode($_COOKIE['user_email']);
            $profileData = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'weight_kg' => $_POST['weight_kg'],
                'height_cm' => $_POST['height_cm'],
                'gender' => $_POST['gender'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'budget_per_week' => $_POST['budget_per_week']
            ];
            $updateResult = $profileModel->updateUserProfile($username, $profileData);

            if ($updateResult) {
                echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update profile.']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
    }
} catch (Exception $e) {
    error_log("Server error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
