<?php
// Setarea header-ului pentru JSON
header('Content-Type: application/json');

// Redirecționarea cererilor în funcție de metoda HTTP
try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            require_once __DIR__ . '/view_basket.php';
            break;
        case 'POST':
            require_once __DIR__ . '/add_to_basket.php';
            break;
        case 'DELETE':
            require_once __DIR__ . '/remove_from_basket.php';
            break;
        default:
            http_response_code(405);
            echo json_encode(['message' => 'Invalid request method']);
            break;
    }
} catch (Exception $e) {
    error_log("Error in TestBasket.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['message' => 'Internal Server Error']);
}
?>
