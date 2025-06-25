<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private $conn;
    
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    
    public function getUserId() {
        // Se o usuário está logado, usa o ID da sessão de login
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            return $_SESSION['user_id'];
        }
        
        // Caso contrário, usa o sistema de sessão anônima para o carrinho
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
            // Para evitar o erro de 'Duplicate entry', usamos o session_id (que é único)
            // como um valor de placeholder para o email do usuário anônimo.
            $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha, session_id) VALUES ('', ?, '', ?)");
            $stmt->bind_param("ss", $session_id, $session_id);
            $stmt->execute();
            return $this->conn->insert_id;
        }
    }
    
    public function authenticate($email, $senha) {
        $stmt = $this->conn->prepare("SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($senha, $user['senha'])) {
                return $user;
            }
        }
        
        return false;
    }
    
    public function createUser($nome, $email, $senha) {
        // Verifica se o email já existe
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return 'Este email já está cadastrado';
        }
        
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'cliente')");
        $stmt->bind_param("sss", $nome, $email, $senha_hash);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return 'Erro ao criar usuário';
        }
    }
}
?>