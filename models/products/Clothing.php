<?php
require_once 'ProductItem.php';

class Clothing extends ProductItem {
    public function getCategoryName() {
        return "Vestuário / Roupa";
    }
}
?>