<?php

class PagesController {
    private $pages = [
        'home' => '/../views/Html/HomePage.html',
        'homeL' => '/../views/Html/HomePageL.html',
        'recipes' => '/../views/Html/Recipes.html',
        'shop' => '/../views/Html/Shop.php',
        'FoodsAdmin' => '/../views/Html/FoodsAdmin.php',
        'UsersAdmin' => '/../views/Html/UsersAdmin.php',
        //'basket' => '/../views/Html/Basket.php',
        'mybasket' => '/../views/Html/Basket.php',
        'metadataManagement' => '/../views/Html/MetadataManagement.html',
        'AdminView' => '/../views/Html/Admin.php',
        'login' => '/../views/Html/Login.html',
        'register' => '/../views/Html/Register.html',
        'forgot' => '/../views/Html/ForgotPassword.html',
        'userInfo' => '/../views/Html/Profile.php',
        'info' => '/../views/Html/Info.html',
        'admin' => '/../controllers/AdminController.php',
        'database' => '/../model/database.php',
        'USER' => '/../controllers/register-controller.php',
        'Session' => '/../controllers/login-controller.php',
        'user-con' => '/../controllers/user-controller.php',
        'foods-database' => '/../model/databases/create_and_populate_foods.php',
        'logout' => '/../controllers/logout.php',
        'foods' => '/../controllers/shop-controller.php',
        'FoodsAdmin' => '/../controllers/FoodsAdminController.php',
        'statistics' => '/../views/Html/Statistics.php',
        'addBasket' => '/../controllers/add_to_basket.php',
        'View' => '/../controllers/view_basket.php',
        'Remove' => '/../controllers/remove_from_basket.php',
        'FoodsAdmin' => '/../views/Html/FoodsAdmin.php',
        'users' => '/../controllers/UserAdminController.php',
        'UsersAdmin' => '/../views/Html/UsersAdmin.php',
        'lists' => '/../controllers/ListsController.php',
        'Lists' => '/../views/Html/lists.html',
        'Basket' => '/../controllers/TestBasket.php',
        'Profile' => '/../controllers/ProfileController.php'
    ];

    public function handleRequest() {
        $page = $_GET['page'] ?? 'homeL';

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
    
    if ($page === '/../controllers/AdminController.php') {
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController();
        $adminController->showAdminPage();
    } elseif ($page === '/../views/Html/FoodsAdmin.php') {
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController();
        $adminController->showFoodsAdminPage();
    } elseif ($page === '/../controllers/ListsController.php') {
        require_once __DIR__ . '/../controllers/ListsController.php';
        $listsController = new ListsController($conn);
        $listsController->handleRequest();
    } else {
        $view->render($page);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
