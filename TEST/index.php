<?php
require_once 'controllers/pages-controller.php';

$controller = new PagesController();
$view = new View();

try {
    $page = $controller->handleRequest();
    $view->render($page);
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
