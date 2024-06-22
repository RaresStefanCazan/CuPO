<?php
// ShopController.php

require_once __DIR__ . '/../model/ShopModel.php';
require_once __DIR__ . '/../model/database.php';

session_start();

// Verific
function verifySession()
{
    if (!isset($_SESSION['username']) || !isset($_COOKIE['user_email'])) {
        return false;
    }
    return $_SESSION['username'] === $_COOKIE['user_email'];
}

// În fiecare script de pagină care necesită autentificare
if (!verifySession()) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}

class ShopController
{
    private $shopModel;

    public function __construct($conn)
    {
        $this->shopModel = new ShopModel($conn);
    }

    public function getFoods()
    {
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

    public function getFoodsSortedByPrice($order)
    {
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

    public function deleteFood($id)
    {
        if ($this->shopModel->deleteFood($id)) {
            echo json_encode(['message' => 'Food deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to delete food']);
        }
    }

    public function updateFood()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id'], $data['aliment'], $data['category'], $data['price'], $data['restrictions'], $data['perishability'], $data['validity'], $data['availability_season'], $data['availability_region'], $data['specific_restaurants'], $data['weight'], $data['protein'], $data['fiber'], $data['calories'])) {
            $id = $data['id'];
            $aliment = $data['aliment'];
            $category = $data['category'];
            $price = $data['price'];
            $restrictions = $data['restrictions'];
            $perishability = $data['perishability'];
            $validity = $data['validity'];
            $availability_season = $data['availability_season'];
            $availability_region = $data['availability_region'];
            $specific_restaurants = $data['specific_restaurants'];
            $weight = $data['weight'];
            $protein = $data['protein'];
            $fiber = $data['fiber'];
            $calories = $data['calories'];

            if ($this->shopModel->updateFood($id, $aliment, $category, $price, $restrictions, $perishability, $validity, $availability_season, $availability_region, $specific_restaurants, $weight, $protein, $fiber, $calories)) {
                echo json_encode(['message' => 'Food updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Failed to update food']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input']);
        }
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
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id'])) {
        $shopController->deleteFood($data['id']);
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Food ID is required']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $shopController->updateFood();
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Invalid request method']);
}
