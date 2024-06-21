<?php
require_once __DIR__ . '/../model/Database.php';
require_once __DIR__ . '/../model/BasketModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['food_id'])) {
        $food_id = $data['food_id'];
        $listId = $_COOKIE['currentListId'] ?? null;

        if ($listId) {
           
            $basketModel = new BasketModel($conn);

            $result = $basketModel->addToBasket($listId, $food_id);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Item added to basket']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error adding item to basket']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'List ID not found']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Food ID is required']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
