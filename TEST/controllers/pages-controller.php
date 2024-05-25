<?php
// Controller pentru pagina "Recipes"
class PagesController {
    public function recipes() {
        echo "Loading Recipes Page";
        // Path to the controller itself, might need to include specific recipe logic file if exists
        require_once __DIR__ . '/../views/Html/Recipes.html';
    }

    public function shoppingList() {
        echo "Loading Shopping List Page";
        require_once __DIR__ . '/../views/Html/ShoppingList.html';
    }

    

    public function handleRequest() {
        // Verificăm dacă 'page' este setat în URL
        if (isset($_GET['page'])) {
            // Obținem pagina din URL
            $page = $_GET['page'];

            // Verificăm dacă metoda există în controller
            if (method_exists($this, $page)) {
                // Apelăm metoda
                $this->$page();
            } else {
                // Dacă metoda nu există, afișăm o eroare sau redirecționăm către o pagină de eroare
                echo "Pagina nu a fost găsită";
            }
        } else {
            // Dacă 'page' nu este setat, putem redirecționa către o pagină implicită
            $this->recipes();
        }
    }
}

// Creăm o instanță a controllerului și gestionăm cererea
$controller = new PagesController();
$controller->handleRequest();
?>