<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            
            if (empty($email) || empty($senha)) {
                $error = 'Por favor, preencha todos os campos';
            } else {
                $user = $this->userModel->authenticate($email, $senha);
                
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nome'];
                    $_SESSION['user_type'] = $user['tipo'];
                    $_SESSION['logged_in'] = true;
                    
                    if ($user['tipo'] === 'admin') {
                        header('Location: admin.php');
                    } else {
                        header('Location: index.php');
                    }
                    exit;
                } else {
                    $error = 'Email ou senha incorretos';
                }
            }
        }
        
        $data = [
            'title' => 'Login - Tecny Geek Store',
            'error' => $error ?? null
        ];
        
        $this->loadView('login', $data);
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $confirmar_senha = $_POST['confirmar_senha'] ?? '';
            
            if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
                $error = 'Por favor, preencha todos os campos';
            } elseif ($senha !== $confirmar_senha) {
                $error = 'As senhas não coincidem';
            } elseif (strlen($senha) < 6) {
                $error = 'A senha deve ter pelo menos 6 caracteres';
            } else {
                $result = $this->userModel->createUser($nome, $email, $senha);
                
                if ($result === true) {
                    $success = 'Cadastro realizado com sucesso! Faça login para continuar.';
                } else {
                    $error = $result;
                }
            }
        }
        
        $data = [
            'title' => 'Cadastro - Tecny Geek Store',
            'error' => $error ?? null,
            'success' => $success ?? null
        ];
        
        $this->loadView('register', $data);
    }
    
    public function logout() {
        session_destroy();
        header('Location: login.php');
        exit;
    }
    
    private function loadView($view, $data = []) {
    extract($data);
    
    // Adiciona uma verificação para o caminho da view de login, que está em um subdiretório
    $path = __DIR__ . '/../views/';
    if ($view === 'login') {
        $path .= 'layout/' . $view . '.php';
    } else {
        $path .= $view . '.php';
    }
    
    require_once $path;
}
}
?>