<?php

class PagesController {
    private $pages = [
       'home' => '/../views/Html/HomePage.html',
        'homeL' => '/../views/Html/HomePageL.html',
        'recipes' => '/../views/Html/Recipes.html',
        'shop' => '/../views/Html/Shop.php',
        'basket' => '/../views/Html/Basket.php',
        //'statistics' => '/../views/Html/Statistics.html',
        'mybasket' => '/../views/Html/Basket.php',
        'metadataManagement' => '/../views/Html/MetadataManagement.html',
        'admin' => '/../views/Html/Admin.php',
        'login' => '/../views/Html/Login.html',
        'register' => '/../views/Html/Register.html',
        'forgot' => '/../views/Html/ForgotPassword.html',
        'userInfo' => '/../views/Html/UserInfo.html',
        'info' => '/../views/Html/Info.html',
        'admin' => '/../controllers/AdminController.php',
        'database' => '/../model/database.php',
        'register-con' => '/../controllers/register-controller.php',
        'login-con' => '/../controllers/login-controller.php',
        'foods-database' => '/../model/databases/create_and_populate_foods.php',
        'logout' => '/../controllers/logout.php',
        'foods' => '/../controllers/shop-controller.php',
        'FoodsAdmin' => '/../controllers/FoodsAdminController.php',
        'statistics' => '/../views/Html/Statistics.php',
        'Basket' => '/../controllers/add_to_basket.php',
        'View' => '/../controllers/view_basket.php',
        'Remove' => '/../controllers/remove_from_basket.php',
        'FoodsAdmin' => '/../views/Html/FoodsAdmin.php',
         'users' => '/../controllers/UserAdminController.php',
        'UsersAdmin' => '/../views/Html/UsersAdmin.php'
       
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
    //verific inainte sa poata intra pe admin.php,
    // ii fac obiecte de pe admincontroller si verific daca e admin

    if ($page === '/../controllers/AdminController.php') {
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController();
        $adminController->showAdminPage();
    } elseif ($page === '/../controllers/UserAdminController.php') {
        require_once __DIR__ . '/../controllers/UserAdminController.php';
        $usersAdminController = new UsersAdminController($conn);
        $usersAdminController->getUsers();
    } else {
        $view->render($page);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
