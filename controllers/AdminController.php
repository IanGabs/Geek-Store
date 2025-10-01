<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../adapters/DataExporter.php';
require_once __DIR__ . '/../adapters/JsonExporter.php';
require_once __DIR__ . '/../adapters/CsvConverter.php';
require_once __DIR__ . '/../adapters/CsvAdapter.php';

class AdminController {
    private $productModel;
    private $cartModel;
    
    public function __construct() {
        $this->checkAuth();
        $this->productModel = new Product();
        $this->cartModel = new Cart();
    }
    
    private function checkAuth() {
        // Garante que a sessão está iniciada antes de verificar
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Se o utilizador não estiver logado ou não for admin, redireciona
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
            $desconto = floatval($_POST['desconto'] ?? 0);
        
            if (!empty($nome) && !empty($descricao) && $preco > 0 && !empty($imagem)) {
                $this->productModel->addProduct($nome, $descricao, $preco, $imagem, $desconto);
                $_SESSION['admin_message'] = 'Produto adicionado com sucesso!';
            } else {
                $_SESSION['admin_error'] = 'Por favor, preencha todos os campos corretamente.';
            }
        } elseif ($action === 'delete') {
            // ... (código de exclusão)
        }
    
        header('Location: admin.php');
        exit;
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }

    public function exportProducts($format)
    {
        $products = $this->productModel->getAllProducts();
        $exporter = null;

        switch($format)
        {
            case 'csv':
                $adaptee = new CsvConverter();
                $exporter = new CsvAdapter($adaptee);
                $contentType = 'text/csv';
                $fileName = 'produtos.csv';
                break;
            
            case 'json':
            default:
                $exporter = new JsonExporter();
                $contentType = 'application/json';
                $fileName = 'produtos.json';
                break;
        }

        $data = $exporter->export($products);

        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo $data;
        exit;
    }
}
?>