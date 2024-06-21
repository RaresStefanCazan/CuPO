<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../model/BasketModel.php';
require_once __DIR__ . '/../model/database.php';

$basketModel = new BasketModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['food_id']) && isset($data['list_id'])) {
        $foodId = $data['food_id'];
        $listId = $data['list_id'];

        if ($basketModel->addToBasket($listId, $foodId)) {
            echo json_encode(['message' => 'Item added to cart']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to add item to cart']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Food ID and List ID are required']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Invalid request method']);
}
?>
