<?php
session_start();

// Mostra todos os erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug do Admin</h1>";

// Verifica sessão
echo "<h2>1. Sessão:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Verifica se está logado como admin
echo "<h2>2. Status de Login:</h2>";
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo "✅ Usuário logado<br>";
    echo "Nome: " . $_SESSION['user_name'] . "<br>";
    echo "Tipo: " . $_SESSION['user_type'] . "<br>";
    
    if ($_SESSION['user_type'] === 'admin') {
        echo "✅ É administrador<br>";
    } else {
        echo "❌ NÃO é administrador<br>";
    }
} else {
    echo "❌ Usuário NÃO logado<br>";
}

// Testa conexão com banco
echo "<h2>3. Conexão com Banco:</h2>";
try {
    require_once 'config/Database.php';
    $db = Database::getInstance();
    $conn = $db->getConnection();
    echo "✅ Conexão com banco OK<br>";
    
    // Testa modelo de produtos
    echo "<h2>4. Produtos no Banco:</h2>";
    require_once 'models/Product.php';
    $productModel = new Product();
    $produtos = $productModel->getAllProducts();
    echo "Total de produtos: " . count($produtos) . "<br>";
    echo "<pre>";
    print_r($produtos);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
}

// Verifica arquivos
echo "<h2>5. Arquivos necessários:</h2>";
$files = [
    'controllers/AdminController.php',
    'views/admin.php',
    'export.php',
    'adapters/DataExporter.php',
    'adapters/JsonExporter.php',
    'adapters/CsvConverter.php',
    'adapters/CsvAdapter.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file<br>";
    } else {
        echo "❌ $file NÃO ENCONTRADO<br>";
    }
}
?>