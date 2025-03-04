<?php


require_once __DIR__ . '/../model/StatisticsModel.php';
require_once __DIR__ . '/../model/database.php';

session_start();


function verifySession() {
    if (!isset($_SESSION['username']) || !isset($_COOKIE['user_email'])) {
        return false;
    }
    return urldecode($_SESSION['username']) === urldecode($_COOKIE['user_email']);
}


if (!verifySession()) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}

class StatisticsController {
    private $statisticsModel;

    public function __construct($conn) {
        $this->statisticsModel = new StatisticsModel($conn);
    }

    public function getUserStatistics() {
        header('Content-Type: application/json');

        $username = urldecode($_SESSION['username']);
        
        
        $userData = $this->statisticsModel->getUserData($username);

        if ($userData) {
            $height_cm = $userData['height_cm'];
            $weight_kg = $userData['weight_kg'];

            // calculeaza BMI
            $bmi = $this->statisticsModel->calculateBMI($height_cm, $weight_kg);

            // interpreteaza BMI 
            $bmiCategory = $this->statisticsModel->interpretBMI($bmi);

            echo json_encode([
                'status' => 'success',
                'data' => [
                    'height_cm' => $height_cm,
                    'weight_kg' => $weight_kg,
                    'bmi' => $bmi,
                    'bmi_category' => $bmiCategory
                ]
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'No statistics found for the user.']);
        }
    }
}

$statisticsController = new StatisticsController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $statisticsController->getUserStatistics();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
