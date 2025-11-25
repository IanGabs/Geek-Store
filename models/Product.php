<?php
require_once __DIR__ . '/../config/Database.php';

class Product {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    private function calcularPrecoComDesconto($produto) {
        if (isset($produto['desconto']) && $produto['desconto'] > 0) {
            $produto['preco_final'] = $produto['preco'] * (1 - ($produto['desconto'] / 100));
        } else {
            $produto['preco_final'] = $produto['preco'];
        }
        return $produto;
    }

    public function getAllProducts() {
        $stmt = $this->conn->prepare("SELECT * FROM produtos ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $produtos = [];
        while ($row = $result->fetch_assoc()) {
            $produtos[] = $this->calcularPrecoComDesconto($row);
        }
        
        return $produtos;
    }
    
    public function getFeaturedProducts($limit = 4) {
        $stmt = $this->conn->prepare("SELECT * FROM produtos ORDER BY id DESC LIMIT ?");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $produtos = [];
        while ($row = $result->fetch_assoc()) {
            $produtos[] = $this->calcularPrecoComDesconto($row);
        }
        
        return $produtos;
    }
    
    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $produto = $result->fetch_assoc();
            return $this->calcularPrecoComDesconto($produto);
        }
        
        return null;
    }
    
    // ATUALIZADO: Aceita o parâmetro $categoria
    public function addProduct($nome, $descricao, $preco, $imagem, $desconto = 0, $categoria = '') {
        
        $stmt = $this->conn->prepare("INSERT INTO produtos (nome, descricao, preco, imagem, desconto, categoria) VALUES (?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("ssdsds", $nome, $descricao, $preco, $imagem, $desconto, $categoria);
        
        return $stmt->execute();
    }
    
    public function deleteProduct($id) {
        $stmt = $this->conn->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
}
?>