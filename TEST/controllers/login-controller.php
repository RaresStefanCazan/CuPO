<?php
require_once __DIR__ . '/../model/database.php';
require_once __DIR__ . '/../model/UserModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $username = $_POST['email'];
        $password = $_POST['password'];

        $userModel = new UserModel($conn);
        if ($userModel->login($username, $password)) {
            // Login successful
            header("Location: /home/homeL");
            exit();
        } else {
            
            header("Location: /home/login");
            echo "Invalid username orr password";
            exit();
        }
    } else {
        echo "Email or password not set in the form";
    }
} else {
    echo "Invalid request method";
}
?>