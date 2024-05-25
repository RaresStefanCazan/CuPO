<?php
class HomeController {
    public function index() {
        require_once '../views/Html/HomePage.html';
    }
}

class RecipesController {
    public function index() {
        require_once '../views/Html/Recipes.html';
    }
}

class CulinaryPreferencesController {
    public function index() {
        require_once '../views/Html/CulinaryPreferences.html';
    }
}
?>
