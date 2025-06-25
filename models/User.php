<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private $conn;
    
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    
    public function getUserId() {
        if (!isset($_SESSION['session_id'])) {
            $_SESSION['session_id'] = session_id();
        }
        
        $session_id = $_SESSION['session_id'];
        
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE session_id = ?");
        $stmt->bind_param("s", $session_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        } else {
            $stmt = $this->conn->prepare("INSERT INTO usuarios (session_id) VALUES (?)");
            $stmt->bind_param("s", $session_id);
            $stmt->execute();
            return $this->conn->insert_id;
        }
    }
}
?>