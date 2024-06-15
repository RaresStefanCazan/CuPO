<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../model/database.php';
require_once __DIR__ . '/../model/BasketModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $basketModel = new BasketModel($conn);
    $basketItems = $basketModel->getBasketItems($_SESSION['basket'] ?? []);
    echo json_encode($basketItems);
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Invalid request method']);
}
?>
