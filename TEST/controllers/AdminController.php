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

        // Render the admin view
        $this->renderAdminPage();
    }

    private function renderAdminPage() {
        require_once __DIR__ . '/../views/Html/Admin.php';
    }
}
?>
