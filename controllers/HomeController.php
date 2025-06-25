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
    
    // MODIFICAÇÃO: Lógica para tratar ações do admin (adicionar produto)
    private function handleAdminActions() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'add_product') {
                $nome = $_POST['nome'] ?? '';
                $descricao = $_POST['descricao'] ?? '';
                $preco = floatval($_POST['preco'] ?? 0);
                $imagem = $_POST['imagem'] ?? '';
                
                if (!empty($nome) && !empty($descricao) && $preco > 0 && !empty($imagem)) {
                    $this->productModel->addProduct($nome, $descricao, $preco, $imagem);
                    $_SESSION['message'] = 'Produto adicionado com sucesso!';
                } else {
                    $_SESSION['error'] = 'Por favor, preencha todos os campos corretamente.';
                }
                
                header('Location: index.php');
                exit;
            }
        }
    }

    public function index() {
        // MODIFICAÇÃO: Chama a função que trata as ações do admin
        $this->handleAdminActions();

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