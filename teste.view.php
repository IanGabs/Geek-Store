<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simula dados que seriam passados pelo controller
$title = "Teste Admin";
$user_name = "Administrador";
$produtos = [
    ['id' => 1, 'nome' => 'Produto Teste', 'descricao' => 'Descrição teste', 'preco' => 99.90, 'imagem' => './imgs/Phoenica.png']
];

echo "<h1>TESTE: Carregando view admin.php</h1>";
echo "<p>Se você ver o Painel Administrativo abaixo, a view está funcionando.</p>";
echo "<hr>";

// Tenta carregar a view
if (file_exists('views/admin.php')) {
    echo "<p style='color: green;'>✅ Arquivo views/admin.php ENCONTRADO</p>";
    echo "<p>Tamanho: " . filesize('views/admin.php') . " bytes</p>";
    echo "<p>Última modificação: " . date("d/m/Y H:i:s", filemtime('views/admin.php')) . "</p>";
    echo "<hr>";
    
    // Carrega a view
    require_once 'views/admin.php';
} else {
    echo "<p style='color: red;'>❌ Arquivo views/admin.php NÃO ENCONTRADO</p>";
}
?>