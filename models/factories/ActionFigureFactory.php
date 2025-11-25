<?php
require_once 'ProductFactory.php';
require_once __DIR__ . '/../products/ActionFigure.php';

class ActionFigureFactory extends ProductFactory {
    public function createProduct($nome, $preco, $descricao) {
        return new ActionFigure($nome, $preco, $descricao);
    }
}
?>