<?php
require_once __DIR__ . '/../model/database.php';
require_once __DIR__ . '/../model/StatisticsModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $conn = new mysqli("localhost:3307", "root", "", "cupo_users"); // Adaugă portul 3307

    if ($conn->connect_error) {
        echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    $controller = new StatisticsController($conn);
    $data = [];

    if ($type === 'expensive') {
        $data = $controller->getExpensiveProducts();
    } elseif ($type === 'favourites') {
        $data = $controller->getFavouriteProducts();
    } elseif ($type === 'vegan') {
        $data = $controller->getVeganProducts();
    } elseif ($type === 'lactosefree') {
        $data = $controller->getLactoseFreeProducts();
    }

    echo json_encode($data);
    exit();
}

class StatisticsController {
    private $statisticsModel;

    public function __construct($conn) {
        $this->statisticsModel = new StatisticsModel($conn);
    }

    public function getExpensiveProducts() {
        return $this->statisticsModel->getExpensiveProducts();
    }

    public function getFavouriteProducts() {
        return $this->statisticsModel->getFavouriteProducts();
    }

    public function getVeganProducts() {
        return $this->statisticsModel->getVeganProducts();
    }

    public function getLactoseFreeProducts() {
        return $this->statisticsModel->getLactoseFreeProducts();
    }
}
?>
