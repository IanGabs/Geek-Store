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
            $desconto = floatval($_POST['desconto'] ?? 0);
            
            $imagemFinalPath = '';
            
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) 
                {
                $targetDir = __DIR__ . '/../imgs/';
                
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                $fileInfo = pathinfo($_FILES['imagem']['name']);
                $extension = strtolower($fileInfo['extension']);
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($extension, $allowedExtensions)) {
                    $newFileName = uniqid('prod_') . '.' . $extension;
                    $targetFilePath = $targetDir . $newFileName;
                    
                    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $targetFilePath)) {
                        $imagemFinalPath = './imgs/' . $newFileName;
                    } else {
                        $_SESSION['admin_error'] = 'Falha ao salvar o arquivo no servidor.';
                        header('Location: admin.php?page=new');
                        exit;
                    }
                } else {
                    $_SESSION['admin_error'] = 'Formato de imagem inválido. Use JPG, PNG, GIF ou WEBP.';
                    header('Location: admin.php?page=new');
                    exit;
                }
            } else {
                $_SESSION['admin_error'] = 'Por favor, selecione uma imagem válida.';
                header('Location: admin.php?page=new');
                exit;
            }

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

            if ($factory && !empty($imagemFinalPath)) {
                $produtoObj = $factory->createProduct($nome, $preco, $descricao);
                $categoriaAutomatica = $produtoObj->getCategoryName();

                if (!empty($nome) && $preco > 0) {
                    $this->productModel->addProduct($nome, $descricao, $preco, $imagemFinalPath, $desconto, $categoriaAutomatica);
                    $_SESSION['admin_message'] = "Produto criado com sucesso! Imagem enviada.";
                    header('Location: admin.php');
                    exit;
                }
            }
            
            $_SESSION['admin_error'] = 'Preencha todos os campos corretamente.';
            header('Location: admin.php?page=new');
            exit;
        }
    }

    public function delete() {
        $id = $_POST['product_id'] ?? 0;
        
        // $produto = $this->productModel->getProductById($id);
        // if ($produto && file_exists(__DIR__ . '/../' . $produto['imagem'])) { unlink(...); }

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