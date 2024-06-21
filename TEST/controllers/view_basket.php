<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../model/database.php';
require_once __DIR__ . '/../model/BasketModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $listId = isset($_GET['list_id']) ? intval($_GET['list_id']) : null;

    if (!$listId) {
        http_response_code(400);
        echo json_encode(['message' => 'List ID is required']);
        exit;
    }

    error_log("Fetching items for list ID: $listId"); // Debugging: logăm ID-ul listei

    $basketModel = new BasketModel($conn);
    $basketItems = $basketModel->getBasketItemsByListId($listId);
    error_log("Basket items fetched: " . json_encode($basketItems)); // Debugging: logăm produsele din coș
    echo json_encode($basketItems);
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Invalid request method']);
}
?>
