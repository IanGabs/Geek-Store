<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/User.php';

class Cart {
    private $conn;
    private $user;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
        $this->user = new User();
    }
    
    public function addItem($produto_id) {
        $usuario_id = $this->user->getUserId();
        
        $stmt = $this->conn->prepare("SELECT * FROM carrinho WHERE usuario_id = ? AND produto_id = ?");
        $stmt->bind_param("ii", $usuario_id, $produto_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $stmt = $this->conn->prepare("UPDATE carrinho SET quantidade = quantidade + 1 WHERE usuario_id = ? AND produto_id = ?");
            $stmt->bind_param("ii", $usuario_id, $produto_id);
        } else {
            $stmt = $this->conn->prepare("INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES (?, ?, 1)");
            $stmt->bind_param("ii", $usuario_id, $produto_id);
        }
        
        return $stmt->execute();
    }
    
    public function updateQuantity($produto_id, $acao) {
        $usuario_id = $this->user->getUserId();
        
        if ($acao === 'aumentar') {
            $stmt = $this->conn->prepare("UPDATE carrinho SET quantidade = quantidade + 1 WHERE usuario_id = ? AND produto_id = ?");
            $stmt->bind_param("ii", $usuario_id, $produto_id);
        } else if ($acao === 'diminuir') {
            $stmt = $this->conn->prepare("SELECT quantidade FROM carrinho WHERE usuario_id = ? AND produto_id = ?");
            $stmt->bind_param("ii", $usuario_id, $produto_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['quantidade'] > 1) {
                $stmt = $this->conn->prepare("UPDATE carrinho SET quantidade = quantidade - 1 WHERE usuario_id = ? AND produto_id = ?");
                $stmt->bind_param("ii", $usuario_id, $produto_id);
            } else {
                $stmt = $this->conn->prepare("DELETE FROM carrinho WHERE usuario_id = ? AND produto_id = ?");
                $stmt->bind_param("ii", $usuario_id, $produto_id);
            }
        }
        
        return $stmt->execute();
    }
    
    public function removeItem($produto_id) {
        $usuario_id = $this->user->getUserId();
        
        $stmt = $this->conn->prepare("DELETE FROM carrinho WHERE usuario_id = ? AND produto_id = ?");
        $stmt->bind_param("ii", $usuario_id, $produto_id);
        
        return $stmt->execute();
    }
    
    public function getTotalItems() {
        $usuario_id = $this->user->getUserId();
        
        $stmt = $this->conn->prepare("SELECT SUM(quantidade) as total FROM carrinho WHERE usuario_id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'] ? $row['total'] : 0;
    }
    
    public function getItems() {
        $usuario_id = $this->user->getUserId();
        
        $stmt = $this->conn->prepare("
            SELECT c.*, p.nome, p.preco, p.imagem
            FROM carrinho c
            JOIN produtos p ON c.produto_id = p.id
            WHERE c.usuario_id = ?
        ");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $itens = [];
        $total = 0;
        
        while ($row = $result->fetch_assoc()) {
            $subtotal = $row['preco'] * $row['quantidade'];
            $total += $subtotal;
            
            $itens[] = [
                'id' => $row['id'],
                'produto_id' => $row['produto_id'],
                'quantidade' => $row['quantidade'],
                'nome' => $row['nome'],
                'preco' => $row['preco'],
                'imagem' => $row['imagem'],
                'subtotal' => $subtotal
            ];
        }
        
        return [
            'itens' => $itens,
            'total' => $total
        ];
    }
}
?>