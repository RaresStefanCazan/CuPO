<?php
require_once __DIR__ . '/../model/ShopModel.php';
require_once __DIR__ . '/../model/database.php';

class ShopController {
    private $shopModel;

    public function __construct($conn) {
        $this->shopModel = new ShopModel($conn);
    }

    public function getFoods() {
        header('Content-Type: application/json');
        $foods = $this->shopModel->getFoods();
        echo json_encode($foods);
    }
}

// Inițializarea controllerului
$shopController = new ShopController($conn);

// Verificarea tipului de cerere
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $shopController->getFoods();
}
?>
