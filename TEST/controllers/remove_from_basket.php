<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../model/database.php';
require_once __DIR__ . '/../model/BasketModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['food_id']) && isset($data['list_id'])) {
        $food_id = intval($data['food_id']);
        $list_id = intval($data['list_id']);

        $basketModel = new BasketModel($conn);
        $result = $basketModel->removeFromBasket($list_id, $food_id);

        if ($result) {
            echo json_encode(['message' => 'Item removed from cart']);
        } else {
            echo json_encode(['message' => 'Item not found in cart or could not be removed']);
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
