<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function getUserId() {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            return $_SESSION['user_id'];
        }
        
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
            $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha, session_id) VALUES ('', ?, '', ?)");
            $stmt->bind_param("ss", $session_id, $session_id);
            $stmt->execute();
            return $this->conn->insert_id;
        }
    }
    
    public function authenticate($email, $senha) {
        $stmt = $this->conn->prepare("SELECT id, nome, email, senha, tipo, foto_perfil FROM usuarios WHERE email = ?");
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
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) return 'Este email já está cadastrado';
        
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'cliente')");
        $stmt->bind_param("sss", $nome, $email, $senha_hash);
        
        return $stmt->execute() ? true : 'Erro ao criar usuário';
    }

    public function getUserData($id) {
        $stmt = $this->conn->prepare("SELECT id, nome, email, tipo, foto_perfil, created_at FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateProfile($id, $nome, $email) {
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) return "Este email já está em uso.";

        $stmt = $this->conn->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nome, $email, $id);
        return $stmt->execute();
    }

    public function updatePassword($id, $senhaAtual, $novaSenha) {
        $stmt = $this->conn->prepare("SELECT senha FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!password_verify($senhaAtual, $user['senha'])) {
            return "Senha atual incorreta.";
        }

        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $id);
        return $stmt->execute();
    }

    public function updatePhoto($id, $caminhoFoto) {
        $stmt = $this->conn->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
        $stmt->bind_param("si", $caminhoFoto, $id);
        return $stmt->execute();
    }
}
?>