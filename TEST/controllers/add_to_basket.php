<?php
session_start();


function verifySession() {
    if (!isset($_SESSION['username']) || !isset($_COOKIE['user_email'])) {
        return false;
    }
    return $_SESSION['username'] === $_COOKIE['user_email'];
}


if (!verifySession()) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');
require_once __DIR__ . '/../model/BasketModel.php';
require_once __DIR__ . '/../model/database.php';

$basketModel = new BasketModel($conn);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

      
        error_log("Data received in add_to_basket.php: " . json_encode($data));

        if (isset($data['food_id']) && isset($data['list_id']) && isset($data['quantity'])) {
            $foodId = $data['food_id'];
            $listId = $data['list_id'];
            $quantity = $data['quantity'];

            if ($basketModel->addToBasket($listId, $foodId, $quantity)) {
                echo json_encode(['message' => 'Item added to cart']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Failed to add item to cart']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Food ID, List ID, and quantity are required']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['message' => 'Invalid request method']);
    }
} catch (Exception $e) {
    error_log("Error in add_to_basket.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['message' => 'Internal Server Error']);
}
?>
