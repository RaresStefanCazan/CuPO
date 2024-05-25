<?php
// Front controller pentru gestionarea tuturor cererilor

// Includeți fișierele necesare
require_once '../controllers/pages-controller.php'; // Am corectat numele fișierului pentru a se potrivi cu numele folosit în fișierul `require_once`.

// Obținerea rutei din URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($uri, '/'));

// Router simplu pentru gestionarea rutelor
if (empty($uri[1])) {
    $controller = new HomeController();
    $controller->index();
} elseif ($uri[1] === 'recipes') {
    $controller = new RecipesController();
    $controller->index();
} elseif ($uri[1] === 'culinary-preferences') {
    $controller = new CulinaryPreferencesController();
    $controller->index();
} else {
    // Pagină 404
    header("HTTP/1.0 404 Not Found");
    echo "Page not found";
}
?>
