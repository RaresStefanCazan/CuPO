<?php
require_once __DIR__ . '/../model/database.php';
require_once __DIR__ . '/../model/UserModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['email']) && isset($data['password'])) {
        $username = $data['email'];
        $password = $data['password'];

        $userModel = new UserModel($conn);
        if ($userModel->login($username, $password)) {
            echo json_encode(['message' => 'Login successful']);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid username or password']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Email or password not set in the request']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Invalid request method']);
}
?>
