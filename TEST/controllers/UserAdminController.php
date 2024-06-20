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
}

// Route the request
$usersAdminController = new UsersAdminController($conn);
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $usersAdminController->getUsers();
}
?>
