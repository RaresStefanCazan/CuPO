<?php
// ProfileController.php

require_once __DIR__ . '/../model/ProfileModel.php';
require_once __DIR__ . '/../model/database.php';

session_start();

// Verify Session
function verifySession() {
    if (!isset($_SESSION['username']) || !isset($_COOKIE['user_email'])) {
        return false;
    }
    return urldecode($_SESSION['username']) === urldecode($_COOKIE['user_email']);
}

// Authentication check
if (!verifySession()) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}

class ProfileController {
    private $profileModel;

    public function __construct($conn) {
        $this->profileModel = new ProfileModel($conn);
    }

    public function getProfile($username) {
        header('Content-Type: application/json');

        $profile = $this->profileModel->getUserProfile($username);

        if ($profile) {
            echo json_encode(['status' => 'success', 'data' => $profile]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'No profile found for the user.']);
        }
    }

    public function updateProfile($data) {
        header('Content-Type: application/json');

        if (isset($data['first_name'], $data['last_name'], $data['email'], $data['weight_kg'], $data['height_cm'], $data['gender'], $data['phone'], $data['address'], $data['budget_per_week'])) {
            $username = urldecode($_SESSION['username']);
            $profileData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'weight_kg' => $data['weight_kg'],
                'height_cm' => $data['height_cm'],
                'gender' => $data['gender'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'budget_per_week' => $data['budget_per_week']
            ];

            if ($this->profileModel->updateUserProfile($username, $profileData)) {
                echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to update profile']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
    }
}

// Initialize the controller
$profileController = new ProfileController($conn);

// Check request method and route accordingly
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['username'])) {
        $profileController->getProfile(urldecode($_SESSION['username']));
    } else {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $profileController->updateProfile($data);
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
