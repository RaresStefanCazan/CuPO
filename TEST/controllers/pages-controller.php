<?php

class PagesController {
    private $pages = [
        'home' => '/../views/Html/HomePage.html',
        'recipes' => '/../views/Html/Recipes.html',
        'shoppingList' => '/../views/Html/ShoppingList.html',
        'statistics' => '/../views/Html/Statistics.html',
        'metadataManagement' => '/../views/Html/MetadataManagement.html',
        'admin' => '/../views/Html/Admin.html',
        'login' => '/../views/Html/Login.html',
        'register' => '/../views/Html/Register.html',
        'forgot' => '/../views/Html/ForgotPassword.html',
        'userInfo' => '/../views/Html/UserInfo.html',
        'info' => '/../views/Html/Info.html',
    ];

    public function handleRequest() {
        $page = $_GET['page'] ?? 'recipes';

        if (array_key_exists($page, $this->pages)) {
            return $this->pages[$page];
        } else {
            throw new Exception("Pagina nu a fost găsită");
        }
    }
}

class View {
    public function render($page) {
        require_once __DIR__ . $page;
    }
}

try {
    $controller = new PagesController();
    $view = new View();

    $page = $controller->handleRequest();
    $view->render($page);
} catch (Exception $e) {
    echo $e->getMessage();
}

?>
