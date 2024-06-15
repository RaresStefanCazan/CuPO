<?php
session_start();

// Verifică dacă coșul de cumpărături există în sesiune, dacă nu, creează-l
if (!isset($_SESSION['basket'])) {
    $_SESSION['basket'] = [];
}

// Adaugă alimentul în coș
if (isset($_POST['aliment_id'])) {
    $aliment_id = $_POST['aliment_id'];
    if (!in_array($aliment_id, $_SESSION['basket'])) {
        $_SESSION['basket'][] = $aliment_id;
    }
}

// Redirecționează utilizatorul înapoi la pagina de unde a venit
$referer = $_SERVER['HTTP_REFERER'] ?? '/home/shop';
header('Location: ' . $referer);
exit();
?>
