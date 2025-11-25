<?php
require_once 'ProductFactory.php';
require_once __DIR__ . '/../products/Plush.php';

class PlushFactory extends ProductFactory {
    public function createProduct($nome, $preco, $descricao) {
        return new Plush($nome, $preco, $descricao);
    }
}
?>