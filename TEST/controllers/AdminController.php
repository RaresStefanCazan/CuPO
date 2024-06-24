<?php
require_once __DIR__ . '/../model/database.php';

class AdminController {
    public function __construct() {
        session_start();
    }

    public function showAdminPage() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /home/login');
            exit();
        }

       
        $this->renderAdminPage();
    }

    private function renderAdminPage() {
        require_once __DIR__ . '/../views/php/Admin.php';
    }


    
    public function showFoodsAdminPage() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /home/login');
            exit();
        }

      
        $this->renderFoodsAdminPage();
    }

    private function renderFoodsAdminPage() {
        require_once __DIR__ . '/../views/php/FoodsAdmin.php';
    }
}
?>
