<?php
session_start();

require_once __DIR__ . '/../model/ListModel.php';
require_once __DIR__ . '/../model/database.php';

class ListsController {
    private $listsModel;

    public function __construct($conn) {
        $this->listsModel = new ListsModel($conn);
        $this->checkUser();
    }

    private function checkUser() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /home/login');
            exit();
        }
    }

    public function getLists() {
        header('Content-Type: application/json');
        $lists = $this->listsModel->getListsByUserId($_SESSION['user_id']);
        echo json_encode($lists);
    }

    public function createList() {
        $data = json_decode(file_get_contents('php://input'), true);
        file_put_contents('debug.log', print_r($data, true), FILE_APPEND);

        if (!isset($data['name'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        if ($this->listsModel->createList($data['name'], $_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false]);
        }
    }
}

// Route the request based on the URL
if (isset($_SERVER['PATH_INFO'])) {
    $path = $_SERVER['PATH_INFO'];

    require_once __DIR__ . '/../model/database.php';
    $controller = new ListsController($conn);

    if ($path == '/lists') {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller->getLists();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->createList();
        } else {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
    } else {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Endpoint not found']);
    }
}
?>
