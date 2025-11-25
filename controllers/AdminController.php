<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/factories/ActionFigureFactory.php';
require_once __DIR__ . '/../models/factories/PlushFactory.php';
require_once __DIR__ . '/../models/factories/ClothingFactory.php';
require_once __DIR__ . '/../models/factories/AccessoryFactory.php';
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'admin') {
            header('Location: login.php');
            exit;
        }
    }
    
    public function router() {
        $page = $_GET['page'] ?? 'dashboard';
        $action = $_POST['action'] ?? '';

        if ($action === 'add') {
            $this->store();
            return;
        } elseif ($action === 'delete') {
            $this->delete();
            return;
        }

        if ($page === 'new') {
            $this->create();
        } else {
            $this->index();
        }
    }
    
    public function index() {
        $produtos = $this->productModel->getAllProducts();
        $totalItensCarrinho = $this->cartModel->getTotalItems();
        
        $data = [
            'produtos' => $produtos,
            'totalItensCarrinho' => $totalItensCarrinho,
            'title' => 'Painel Admin - Dashboard',
            'user_name' => $_SESSION['user_name']
        ];
        
        $this->loadView('admin/dashboard', $data);
    }

    public function create() {
        $totalItensCarrinho = $this->cartModel->getTotalItems();
        $data = [
            'totalItensCarrinho' => $totalItensCarrinho,
            'title' => 'Novo Produto - Admin',
            'user_name' => $_SESSION['user_name']
        ];
        $this->loadView('admin/add_product', $data);
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = $_POST['tipo'] ?? '';
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $preco = floatval($_POST['preco'] ?? 0);
            $imagem = $_POST['imagem'] ?? '';
            $desconto = floatval($_POST['desconto'] ?? 0);

            $factory = null;

            switch ($tipo) {
                case 'figure': $factory = new ActionFigureFactory(); break;
                case 'plush':  $factory = new PlushFactory(); break;
                case 'clothing': $factory = new ClothingFactory(); break;
                case 'accessory': $factory = new AccessoryFactory(); break;
                default:
                    $_SESSION['admin_error'] = 'Selecione um tipo de produto válido.';
                    header('Location: admin.php?page=new');
                    exit;
            }

            if ($factory) {
                $produtoObj = $factory->createProduct($nome, $preco, $descricao);
                
                $categoriaAutomatica = $produtoObj->getCategoryName();

                if (!empty($nome) && !empty($descricao) && $preco > 0 && !empty($imagem)) {
                    $this->productModel->addProduct($nome, $descricao, $preco, $imagem, $desconto, $categoriaAutomatica);
                    $_SESSION['admin_message'] = "Produto criado com sucesso! Categoria definida: $categoriaAutomatica";
                    header('Location: admin.php');
                } else {
                    $_SESSION['admin_error'] = 'Preencha todos os campos corretamente.';
                    header('Location: admin.php?page=new');
                }
            }
            exit;
        }
    }

    public function delete() {
        $id = $_POST['product_id'] ?? 0;
        if ($id > 0) {
            $this->productModel->deleteProduct($id);
            $_SESSION['admin_message'] = 'Produto removido com sucesso.';
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

        switch($format) {
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