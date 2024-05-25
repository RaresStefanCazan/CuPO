<?php
    // Controller pentru pagina "Recipes"
    class PagesController {
        public function recipes() {
            require_once('/xampp/htdocs/CuPO/WEB/TEST/views/Html/Recipes.html');
        }
        public function shoppingList() {
            require_once('/xampp/htdocs/CuPO/WEB/TEST/views/Html/ShoppingList.html');
        }
    }

// Creăm o instanță a controllerului
$controller = new PagesController();

// Verificăm dacă 'page' este setat în URL
if (isset($_GET['page'])) {
    // Obținem pagina din URL
    $page = $_GET['page'];

    // Verificăm dacă metoda există în controller
    if (method_exists($controller, $page)) {
        // Apelăm metoda
        $controller->$page();
    } else {
        // Dacă metoda nu există, afișăm o eroare sau redirecționăm către o pagină de eroare
        echo "Pagina nu a fost găsită";
    }
} else {
    // Dacă 'page' nu este setat, putem redirecționa către o pagină implicită
    $controller->recipes();
}
?>
