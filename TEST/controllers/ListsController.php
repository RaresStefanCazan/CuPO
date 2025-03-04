<?php
require_once __DIR__ . '/../model/ListModel.php';
require_once __DIR__ . '/../model/database.php';

session_start();

// Verific
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

class ListsController {
    private $listsModel;

    public function __construct($conn) {
        $this->listsModel = new ListsModel($conn);
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->getLists();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->createList();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $this->addEmailToList();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $this->deleteList();
        } else {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
    }

    public function getLists() {
        $email = $_COOKIE['user_email'] ?? null;
        if (!$email) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            return;
        }

        header('Content-Type: application/json');
        $lists = $this->listsModel->getAllLists($email);
        echo json_encode($lists);
    }

    public function createList() {
        $data = json_decode(file_get_contents('php://input'), true);
    
        if (!isset($data['name']) || !isset($data['group'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }
    
        $email = $_COOKIE['user_email'] ?? null;
        if (!$email) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            return;
        }
    
        $result = $this->listsModel->createList($data['name'], $email, $data['group']);
    
        if ($result) {
            $listId = $this->listsModel->getLastInsertId(); 
            echo json_encode(['success' => true, 'listId' => $listId]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false]);
        }
    }

    public function addEmailToList() {
        $data = json_decode(file_get_contents('php://input'), true);
    
       
        error_log('Received data: ' . print_r($data, true));
    
        if (!isset($data['listId']) || !isset($data['email'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }
    
        $result = $this->listsModel->addEmailToList($data['listId'], $data['email']);
    
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false]);
        }
    }

    public function deleteList() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['listId'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $result = $this->listsModel->deleteList($data['listId']);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to delete list']);
        }
    }
}
?>
