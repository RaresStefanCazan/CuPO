<?php
require_once __DIR__ . '/../model/database.php';
require_once __DIR__ . '/../model/UserModel.php';

// Setarea header-ului pentru a indica că răspunsul este JSON
header('Content-Type: application/json');

// Buffering output to avoid accidental echo or print statements
ob_start();

// Verificăm dacă cererea este de tip POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extragem datele din corpul cererii
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificăm dacă datele necesare (email și parolă) sunt furnizate
    if (isset($data['email']) && isset($data['password'])) {
        $username = $data['email'];
        $password = $data['password'];

        // Inițializăm obiectul UserModel
        $userModel = new UserModel($conn);

        // Apelăm metoda de înregistrare și obținem rezultatul
        $registrationResult = $userModel->register($username, $password);

        // Verificăm rezultatul înregistrării și returnăm răspunsul corespunzător
        if ($registrationResult) {
            // Clear buffer to ensure no extra output is sent
            ob_end_clean();
            echo json_encode(['message' => 'Registration successful']);
            exit();
        } else {
            // În cazul în care înregistrarea a eșuat, întoarce un mesaj de eroare
            http_response_code(400);
            // Clear buffer to ensure no extra output is sent
            ob_end_clean();
            echo json_encode(['message' => 'Registration failed']);
            exit();
        }
    } else {
        // În cazul în care datele nu sunt furnizate corect, întoarce un mesaj de eroare
        http_response_code(400);
        // Clear buffer to ensure no extra output is sent
        ob_end_clean();
        echo json_encode(['message' => 'Email or password not set in the request']);
        exit();
    }
} else {
    // În cazul în care cererea nu este de tip POST, întoarce un mesaj de eroare
    http_response_code(405);
    // Clear buffer to ensure no extra output is sent
    ob_end_clean();
    echo json_encode(['message' => 'Invalid request method']);
    exit();
}
?>
