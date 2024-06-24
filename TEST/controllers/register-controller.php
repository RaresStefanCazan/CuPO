<?php
require_once __DIR__ . '/../model/database.php';
require_once __DIR__ . '/../model/UserModel.php';


header('Content-Type: application/json');


ob_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

   
    if (isset($data['email']) && isset($data['password'])) {
        $username = $data['email'];
        $password = $data['password'];

       
        $userModel = new UserModel($conn);

       
        $registrationResult = $userModel->register($username, $password);

       
        if ($registrationResult) {
           
            ob_end_clean();
            echo json_encode(['message' => 'Registration successful']);
            exit();
        } else {
            
            http_response_code(400);
           
            ob_end_clean();
            echo json_encode(['message' => 'Registration failed']);
            exit();
        }
    } else {
       
        http_response_code(400);
        
        ob_end_clean();
        echo json_encode(['message' => 'Email or password not set in the request']);
        exit();
    }
} else {
    http_response_code(405);
   
    ob_end_clean();
    echo json_encode(['message' => 'Invalid request method']);
    exit();
}
?>