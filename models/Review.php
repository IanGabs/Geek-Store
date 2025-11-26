<?php
require_once __DIR__ . '/../config/Database.php';

class Review {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function createReview($produto_id, $usuario_id, $nota, $comentario) {
        $stmt = $this->conn->prepare("INSERT INTO avaliacoes (produto_id, usuario_id, nota, comentario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $produto_id, $usuario_id, $nota, $comentario);
        
        return $stmt->execute();
    }
    
    public function getReviewsByProduct($produto_id) {
        $stmt = $this->conn->prepare("
            SELECT a.*, u.nome as usuario_nome, u.foto_perfil 
            FROM avaliacoes a 
            JOIN usuarios u ON a.usuario_id = u.id 
            WHERE a.produto_id = ? 
            ORDER BY a.created_at DESC
        ");
        $stmt->bind_param("i", $produto_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            if (empty($row['usuario_nome'])) {
                $row['usuario_nome'] = 'Cliente Geek';
            }
            $reviews[] = $row;
        }
        return $reviews;
    }

    public function getAverageRating($produto_id) {
        $stmt = $this->conn->prepare("SELECT AVG(nota) as media, COUNT(*) as total FROM avaliacoes WHERE produto_id = ?");
        $stmt->bind_param("i", $produto_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        return [
            'media' => $data['media'] ? $data['media'] : 0,
            'total' => $data['total']
        ];
    }
}
?>