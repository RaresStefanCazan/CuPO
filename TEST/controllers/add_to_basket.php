<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['food_id'])) {
        $aliment_id = $data['food_id'];

        if (!isset($_SESSION['basket'])) {
            $_SESSION['basket'] = [];
        }

        if (!in_array($aliment_id, $_SESSION['basket'])) {
            $_SESSION['basket'][] = $aliment_id;
            echo json_encode(['message' => 'Item added to cart']);
        } else {
            echo json_encode(['message' => 'Item already in cart']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Food ID is required']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Invalid request method']);
}
?>
