<?php
session_start();

require_once __DIR__ . '/../model/UserAdminModel.php';
require_once __DIR__ . '/../model/database.php';

class UsersAdminController {
    private $usersAdminModel;

    public function __construct($conn) {
        $this->usersAdminModel = new UsersAdminModel($conn);
        $this->checkAdmin();
    }

    private function checkAdmin() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /home/login');
            exit();
        }
    }

    public function getUsers() {
        header('Content-Type: application/json');
        $users = $this->usersAdminModel->getUsers();
        echo json_encode($users);
    }

    public function deleteUser($id) {
        if ($this->usersAdminModel->deleteUser($id)) {
            echo json_encode(['message' => 'User deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to delete user']);
        }
    }

    public function updateUser() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id'], $data['first_name'], $data['last_name'], $data['email'], $data['weight_kg'], $data['height_cm'], $data['gender'], $data['role'], $data['phone'], $data['address'], $data['budget_per_week'])) {
            $id = $data['id'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];
            $email = $data['email'];
            $weight_kg = $data['weight_kg'];
            $height_cm = $data['height_cm'];
            $gender = $data['gender'];
            $role = $data['role'];
            $phone = $data['phone'];
            $address = $data['address'];
            $budget_per_week = $data['budget_per_week'];

            if ($this->usersAdminModel->updateUser($id, $first_name, $last_name, $email, $weight_kg, $height_cm, $gender, $role, $phone, $address, $budget_per_week)) {
                echo json_encode(['message' => 'User updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Failed to update user']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input']);
        }
    }
}

// Route the request
$usersAdminController = new UsersAdminController($conn);
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $usersAdminController->getUsers();
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id'])) {
        $usersAdminController->deleteUser($data['id']);
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'User ID is required']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $usersAdminController->updateUser();
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Invalid request method']);
}
?>
