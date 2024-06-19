<?php
require_once __DIR__ . '/../model/ShopModel.php';

class ShopController {
    private $shopModel;

    public function __construct($conn) {
        $this->shopModel = new ShopModel($conn);
    }

    public function getFoods() {
        return $this->shopModel->getFoods();
    }
}
?>
