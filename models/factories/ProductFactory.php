<?php
abstract class ProductFactory {
    abstract public function createProduct($nome, $preco, $descricao);

    public function registerLog($nome) {
        return "Log: Um novo produto '{$nome}' está sendo fabricado pelo sistema.";
    }
}
?>