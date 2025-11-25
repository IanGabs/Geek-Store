<?php
require_once 'ProductFactory.php';
require_once __DIR__ . '/../products/Accessory.php';

class AccessoryFactory extends ProductFactory {
    public function createProduct($nome, $preco, $descricao) {
        return new Accessory($nome, $preco, $descricao);
    }
}
?>