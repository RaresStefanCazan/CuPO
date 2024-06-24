<?php

require_once __DIR__ . '/../model/FoodModel.php';
require_once __DIR__ . '/../model/database.php';

class FoodController {
    private $foodModel;

    public function __construct($conn) {
        $this->foodModel = new FoodModel($conn);
    }

    public function getRecommendedFoods($category) {
        $foods = [];
        switch ($category) {
            case 'gainWeight':
               
                $foods = $this->foodModel->getFoodsAboveCalories(250); 
                break;
            case 'maintainWeight':
              
                $foods = $this->foodModel->getFoodsInRangeCalories(150, 250); 
                break;
            case 'loseWeight':
              
                $foods = $this->foodModel->getFoodsBelowCalories(150); 
                break;
            case 'underweight':
             
                $foods = $this->foodModel->getFoodsAboveCalories(250);
                break;
            default:
               
                $foods = $this->foodModel->getFoodData();
                break;
        }

        
        $foods = array_slice($foods, 0, 10);

        $this->respondWithFoodData($foods);
    }

    public function getMostExpensiveFood() {
        $food = $this->foodModel->getMostExpensiveFood();
        $this->respondWithFoodData($food);
    }

    public function getHighestCaloriesFood() {
        $food = $this->foodModel->getHighestCaloriesFood();
        $this->respondWithFoodData($food);
    }

    public function getLowestCaloriesFood() {
        $food = $this->foodModel->getLowestCaloriesFood();
        $this->respondWithFoodData($food);
    }

    public function getHighestProteinFood() {
        $food = $this->foodModel->getHighestProteinFood();
        $this->respondWithFoodData($food);
    }

    private function respondWithFoodData($data) {
        header('Content-Type: application/json');
        if ($data !== null) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch food data.']);
        }
    }
}


$foodController = new FoodController($conn);


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    switch ($action) {
        case 'most_expensive':
            $foodController->getMostExpensiveFood();
            break;
        case 'highest_calories':
            $foodController->getHighestCaloriesFood();
            break;
        case 'lowest_calories':
            $foodController->getLowestCaloriesFood();
            break;
        case 'highest_protein':
            $foodController->getHighestProteinFood();
            break;
        case 'recommended_foods':
        default:
            $category = $_GET['category'] ?? 'recommended_foods';
            $foodController->getRecommendedFoods($category);
            break;
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>