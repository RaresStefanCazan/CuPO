<?php

class PagesController {
    private $pages = [
        'home' => '/../views/php/HomePage.php',
        'homeL' => '/../views/php/HomePageL.php',
        'recipes' => '/../views/php/Recipes.php',
        'shop' => '/../views/php/Shop.php',
        'FoodsAdmin' => '/../views/php/FoodsAdmin.php',
        'UsersAdmin' => '/../views/php/UsersAdmin.php',
        //'basket' => '/../views/php/Basket.php',
        'mybasket' => '/../views/php/Basket.php',
        'AdminView' => '/../views/php/Admin.php',
        'login' => '/../views/php/Login.php',
        'register' => '/../views/php/Register.php',
        'forgot' => '/../views/php/ForgotPassword.php',
        'userInfo' => '/../views/php/Profile.php',
        'info' => '/../views/php/Info.php',
        'admin' => '/../controllers/AdminController.php',
        'database' => '/../model/database.php',
        'USER' => '/../controllers/register-controller.php',
        'Session' => '/../controllers/login-controller.php',
        'user-con' => '/../controllers/user-controller.php',
        'foods-database' => '/../model/databases/create_and_populate_foods.php',
        'logout' => '/../controllers/logout.php',
        'foods' => '/../controllers/shop-controller.php',
        'FoodsAdmin' => '/../controllers/FoodsAdminController.php',
        'statistics' => '/../views/php/Statistics.php',
        'addBasket' => '/../controllers/add_to_basket.php',
        'View' => '/../controllers/view_basket.php',
        'Remove' => '/../controllers/remove_from_basket.php',
        'FoodsAdmin' => '/../views/php/FoodsAdmin.php',
        'users' => '/../controllers/UserAdminController.php',
        'UsersAdmin' => '/../views/php/UsersAdmin.php',
        'lists' => '/../controllers/ListsController.php',
        'Lists' => '/../views/php/lists.php',
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
    } elseif ($page === '/../views/php/FoodsAdmin.php') {
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
