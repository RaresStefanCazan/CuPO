<?php
session_start();

if (isset($_POST['aliment_id'])) {
    $aliment_id = $_POST['aliment_id'];
    if (($key = array_search($aliment_id, $_SESSION['basket'])) !== false) {
        unset($_SESSION['basket'][$key]);
    }
}

// Redirecționează utilizatorul înapoi la pagina de unde a venit
$referer = $_SERVER['HTTP_REFERER'] ?? '/home/shop';
header('Location: ' . $referer);
exit();
?>
