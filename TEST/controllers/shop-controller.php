<?php
// ShopController.php

require_once __DIR__ . '/../model/ShopModel.php';
require_once __DIR__ . '/../model/database.php';

class ShopController {
    private $shopModel;

    public function __construct($conn) {
        $this->shopModel = new ShopModel($conn);
    }

    public function getFoods() {
        header('Content-Type: application/json');

        if (isset($_GET['query'])) {
            $query = $_GET['query'];
            $foods = $this->shopModel->getFoodsByQuery($query);
        } elseif (isset($_GET['category'])) {
            $category = $_GET['category'];
            $foods = $this->shopModel->getFoodsByCategory($category);
        } else {
            $foods = $this->shopModel->getFoods();
        }

        echo json_encode($foods);
    }

    public function getFoodsSortedByPrice($order) {
        header('Content-Type: application/json');
        
        if ($order === 'low_to_high') {
            $foods = $this->shopModel->getFoodsSortedByPrice('ASC');
        } elseif ($order === 'high_to_low') {
            $foods = $this->shopModel->getFoodsSortedByPrice('DESC');
        } else {
            $foods = $this->shopModel->getFoods();
        }

        echo json_encode($foods);
    }
}

// Initialize the controller
$shopController = new ShopController($conn);

// Check request method and route accordingly
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['sort'])) {
        $sortOrder = $_GET['sort'];
        $shopController->getFoodsSortedByPrice($sortOrder);
    } elseif (isset($_GET['query']) || isset($_GET['category'])) {
        $shopController->getFoods();
    } else {
        $shopController->getFoods();
    }
}
?>
