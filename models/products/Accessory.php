<?php
require_once 'ProductItem.php';

class Accessory extends ProductItem {
    public function getCategoryName() {
        return "Acessório (Chaveiro/Pin)";
    }
}
?>