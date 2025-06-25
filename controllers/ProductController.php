<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Cart.php';

class ProductController {
    private $productModel;
    private $cartModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->cartModel = new Cart();
    }
    
    public function index() {
        $produtos = $this->productModel->getAllProducts();
        $totalItensCarrinho = $this->cartModel->getTotalItems();
        
        $data = [
            'produtos' => $produtos,
            'totalItensCarrinho' => $totalItensCarrinho,
            'title' => 'Produtos - Tecny Geek Store'
        ];
        
        $this->loadView('products', $data);
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>