<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Cart.php';

class ProfileController {
    private $userModel;
    private $cartModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: login.php');
            exit;
        }

        $this->userModel = new User();
        $this->cartModel = new Cart();
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserData($userId);
        $totalItensCarrinho = $this->cartModel->getTotalItems();
        
        $msg_sucesso = '';
        $msg_erro = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'update_info') {
                $nome = $_POST['nome'];
                $email = $_POST['email'];
                $result = $this->userModel->updateProfile($userId, $nome, $email);
                
                if ($result === true) {
                    $_SESSION['user_name'] = $nome;
                    $msg_sucesso = "Perfil atualizado com sucesso!";
                    $user = $this->userModel->getUserData($userId);
                } else {
                    $msg_erro = $result;
                }
            }

            elseif ($action === 'update_password') {
                $senha_atual = $_POST['senha_atual'];
                $nova_senha = $_POST['nova_senha'];
                $confirmar_senha = $_POST['confirmar_senha'];

                if ($nova_senha !== $confirmar_senha) {
                    $msg_erro = "As novas senhas não coincidem.";
                } else {
                    $result = $this->userModel->updatePassword($userId, $senha_atual, $nova_senha);
                    if ($result === true) {
                        $msg_sucesso = "Senha alterada com sucesso!";
                    } else {
                        $msg_erro = $result;
                    }
                }
            }

            elseif ($action === 'upload_photo') {
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

                    if (in_array($ext, $allowed)) {
                        $dir = __DIR__ . '/../imgs/users/';
                        if (!is_dir($dir)) mkdir($dir, 0755, true);

                        $new_name = 'user_' . $userId . '_' . time() . '.' . $ext;
                        
                        if (move_uploaded_file($_FILES['foto']['tmp_name'], $dir . $new_name)) {
                            $path = './imgs/users/' . $new_name;
                            $this->userModel->updatePhoto($userId, $path);
                            $_SESSION['user_photo'] = $path;
                            $msg_sucesso = "Foto de perfil atualizada!";
                            $user['foto_perfil'] = $path;
                        } else {
                            $msg_erro = "Erro ao salvar imagem.";
                        }
                    } else {
                        $msg_erro = "Formato inválido. Use JPG, PNG ou WEBP.";
                    }
                }
            }
        }

        $data = [
            'user' => $user,
            'totalItensCarrinho' => $totalItensCarrinho,
            'title' => 'Minha Conta',
            'msg_sucesso' => $msg_sucesso,
            'msg_erro' => $msg_erro
        ];

        extract($data);
        require_once __DIR__ . '/../views/profile.php';
    }
}
?>