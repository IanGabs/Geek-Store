<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Cart.php';

class HomeController {
    private $productModel;
    private $cartModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->cartModel = new Cart();
    }
    
    public function index() {
        $produtos = $this->productModel->getFeaturedProducts();
        $totalItensCarrinho = $this->cartModel->getTotalItems();
        
        $data = [
            'produtos' => $produtos,
            'totalItensCarrinho' => $totalItensCarrinho,
            'title' => 'Início - Tecny Geek Store'
        ];
        
        $this->loadView('home', $data);
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>