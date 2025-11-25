<?php
require_once 'ProductFactory.php';
require_once __DIR__ . '/../products/Clothing.php';

class ClothingFactory extends ProductFactory {
    public function createProduct($nome, $preco, $descricao) {
        return new Clothing($nome, $preco, $descricao);
    }
}
?>