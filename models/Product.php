<?php
require_once __DIR__ . '/../config/Database.php';

class Product {
    private $conn;
    
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    
    public function getAllProducts() {
        return [
            [
                'id' => 1,
                'nome' => 'Pelúcia Phoenica - Epithet Erased',
                'preco' => 79.90,
                'imagem' => './imgs/Phoenica.png',
                'descricao' => 'Pelúcia oficial de Epithet Erased'
            ],
            [
                'id' => 2,
                'nome' => 'Hollow Knight Mini Figures',
                'preco' => 59.90,
                'imagem' => './imgs/Hollow_Knight_figures-removebg-preview.png',
                'descricao' => 'Pequenos figurinos de Hollow Knight'
            ],
            [
                'id' => 3,
                'nome' => 'ENA Pop-Up',
                'preco' => 109.90,
                'imagem' => './imgs/Ena-removebg-preview.png',
                'descricao' => 'Camiseta de ENA'
            ],
            [
                'id' => 4,
                'nome' => 'Chaveiro Kinger',
                'preco' => 99.90,
                'imagem' => './imgs/Kinger-removebg-preview.png',
                'descricao' => 'Chaveiro de Kinger de The Amazing Digital Circus'
            ],
            [
                'id' => 5,
                'nome' => 'Figura Rimuru',
                'preco' => 119.90,
                'imagem' => './imgs/Rimuru-removebg-preview.png',
                'descricao' => 'Figurino de Rimuru'
            ],
            [
                'id' => 6,
                'nome' => 'Button Pin Bill Cipher',
                'preco' => 74.90,
                'imagem' => './imgs/Bill-removebg-preview.png',
                'descricao' => 'Pin do Bill Cipher'
            ]
        ];
    }
    
    public function getFeaturedProducts($limit = 4) {
        $products = $this->getAllProducts();
        return array_slice($products, 0, $limit);
    }
    
    public function getProductById($id) {
        $products = $this->getAllProducts();
        foreach ($products as $product) {
            if ($product['id'] == $id) {
                return $product;
            }
        }
        return null;
    }
}
?>