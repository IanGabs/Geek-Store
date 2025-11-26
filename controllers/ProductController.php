<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/SentimentAnalyzer.php';

class ProductController {
    private $productModel;
    private $cartModel;
    private $reviewModel;
    private $analyzer;

    public function __construct() {
        $this->productModel = new Product();
        $this->cartModel = new Cart();
        $this->reviewModel = new Review();
        $this->analyzer = new SentimentAnalyzer();
    }

    public function details() {
        $id = $_GET['id'] ?? 0;
        $produto = $this->productModel->getProductById($id);
        
        if (!$produto) { header('Location: produtos.php'); exit; }

        $mensagem_erro = '';
        $mensagem_sucesso = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'avaliar') {
            if (session_status() === PHP_SESSION_NONE) session_start();
            
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                $nota = intval($_POST['nota']);
                $comentario = trim($_POST['comentario']);
                $usuario_id = $_SESSION['user_id'];

                if ($nota >= 1 && $nota <= 5 && !empty($comentario)) {
                    
                    $sentimento = $this->analyzer->analisar($comentario);
                    
                    if ($this->reviewModel->createReview($id, $usuario_id, $nota, $comentario, $sentimento)) {
                        $mensagem_sucesso = "Avaliação enviada! Nossa IA classificou como: " . strtoupper($sentimento);
                    } else {
                        $mensagem_erro = "Erro ao salvar avaliação.";
                    }

                } else {
                    $mensagem_erro = "Preencha nota e comentário.";
                }
            } else {
                $mensagem_erro = "Faça login para avaliar.";
            }
        }

        $reviews = $this->reviewModel->getReviewsByProduct($id);
        $stats = $this->reviewModel->getAverageRating($id);
        $totalItensCarrinho = $this->cartModel->getTotalItems();

        $data = [
            'produto' => $produto,
            'reviews' => $reviews,
            'media_nota' => number_format($stats['media'], 1),
            'total_reviews' => $stats['total'],
            'totalItensCarrinho' => $totalItensCarrinho,
            'title' => $produto['nome'],
            'msg_erro' => $mensagem_erro,
            'msg_sucesso' => $mensagem_sucesso
        ];

        $this->loadView('product_details', $data);
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>