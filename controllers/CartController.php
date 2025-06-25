<?php
require_once __DIR__ . '/../models/Cart.php';

class CartController {
    private $cartModel;
    
    public function __construct() {
        $this->cartModel = new Cart();
    }
    
    public function index() {
        $carrinho_dados = $this->cartModel->getItems();
        $totalItensCarrinho = $this->cartModel->getTotalItems();
        
        $data = [
            'itens_carrinho' => $carrinho_dados['itens'],
            'total' => $carrinho_dados['total'],
            'totalItensCarrinho' => $totalItensCarrinho,
            'title' => 'Carrinho - Tecny Geek Store'
        ];
        
        $this->loadView('cart', $data);
    }
    
    public function add() {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['produto_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'ID do produto não especificado']);
            exit;
        }
        
        $produto_id = $data['produto_id'];
        
        if ($this->cartModel->addItem($produto_id)) {
            $total_itens = $this->cartModel->getTotalItems();
            echo json_encode(['status' => 'success', 'message' => 'Produto adicionado ao carrinho', 'total_itens' => $total_itens]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao adicionar ao carrinho']);
        }
    }
    
    public function update() {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['produto_id']) || !isset($data['acao'])) {
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
            exit;
        }
        
        $produto_id = $data['produto_id'];
        $acao = $data['acao'];
        
        if ($this->cartModel->updateQuantity($produto_id, $acao)) {
            $total_itens = $this->cartModel->getTotalItems();
            $carrinho = $this->cartModel->getItems();
            echo json_encode([
                'status' => 'success', 
                'message' => 'Carrinho atualizado', 
                'total_itens' => $total_itens,
                'carrinho' => $carrinho
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar carrinho']);
        }
    }
    
    public function remove() {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['produto_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'ID do produto não especificado']);
            exit;
        }
        
        $produto_id = $data['produto_id'];
        
        if ($this->cartModel->removeItem($produto_id)) {
            $total_itens = $this->cartModel->getTotalItems();
            $carrinho = $this->cartModel->getItems();
            echo json_encode([
                'status' => 'success', 
                'message' => 'Item removido do carrinho', 
                'total_itens' => $total_itens,
                'carrinho' => $carrinho
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao remover item do carrinho']);
        }
    }
    
    public function get() {
        header('Content-Type: application/json');
        
        $carrinho = $this->cartModel->getItems();
        $total_itens = $this->cartModel->getTotalItems();
        
        echo json_encode([
            'status' => 'success', 
            'total_itens' => $total_itens,
            'carrinho' => $carrinho
        ]);
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>