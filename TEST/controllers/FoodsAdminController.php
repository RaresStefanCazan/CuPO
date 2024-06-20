<?php
session_start();
require_once __DIR__ . '/../model/FoodsAdminModel.php';
require_once __DIR__ . '/../model/database.php';

class FoodsAdminController {
private $foodsAdminModel;


public function __construct($conn) {
    $this->foodsAdminModel = new FoodsAdminModel($conn);
    $this->checkAdmin();
}

private function checkAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: /home/login');
        exit();
    }
}

public function getFoods() {
    header('Content-Type: application/json');
    $foods = $this->foodsAdminModel->getFoods();
    echo json_encode($foods);
}

public function deleteFood($id) {
    if ($this->foodsAdminModel->deleteFoodById($id)) {
        echo json_encode(['message' => 'Food deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to delete food']);
    }
}
}

// Route the request
$foodsAdminController = new FoodsAdminController($conn);
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
$foodsAdminController->getFoods();
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['id'])) {
$foodsAdminController->deleteFood($data['id']);
} else {
http_response_code(400);
echo json_encode(['message' => 'Invalid request']);
}
}
?>