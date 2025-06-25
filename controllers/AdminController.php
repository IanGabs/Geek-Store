<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Cart.php';

class AdminController {
    private $productModel;
    private $cartModel;
    
    public function __construct() {
        $this->checkAuth();
        $this->productModel = new Product();
        $this->cartModel = new Cart();
    }
    
    private function checkAuth() {
        if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'admin') {
            header('Location: login.php');
            exit;
        }
    }
    
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleProductAction();
        }
        
        $produtos = $this->productModel->getAllProducts();
        $totalItensCarrinho = $this->cartModel->getTotalItems();
        
        $data = [
            'produtos' => $produtos,
            'totalItensCarrinho' => $totalItensCarrinho,
            'title' => 'Painel Admin - Tecny Geek Store',
            'user_name' => $_SESSION['user_name']
        ];
        
        $this->loadView('admin', $data);
    }
    
    private function handleProductAction() {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'add') {
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $preco = floatval($_POST['preco'] ?? 0);
            $imagem = $_POST['imagem'] ?? '';
            
            if (!empty($nome) && !empty($descricao) && $preco > 0 && !empty($imagem)) {
                $this->productModel->addProduct($nome, $descricao, $preco, $imagem);
                $_SESSION['admin_message'] = 'Produto adicionado com sucesso!';
            } else {
                $_SESSION['admin_error'] = 'Por favor, preencha todos os campos corretamente.';
            }
        } elseif ($action === 'delete') {
            $id = intval($_POST['product_id'] ?? 0);
            if ($id > 0) {
                $this->productModel->deleteProduct($id);
                $_SESSION['admin_message'] = 'Produto removido com sucesso!';
            }
        }
        
        header('Location: admin.php');
        exit;
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>