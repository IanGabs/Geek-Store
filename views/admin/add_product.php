<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="icon" href="./imgs/Tecny__1_-removebg-preview.png" type="image/png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>

    <main class="admin-container">
        <div class="admin-content-wrapper">
            <div class="form-header">
                <h1><i class="fas fa-box-open"></i> Cadastrar Novo Produto</h1>
                <p>Selecione o tipo e preencha os detalhes para fabricar um novo item.</p>
            </div>

            <?php if (isset($_SESSION['admin_error'])): ?>
                <div class="error-message"><?php echo $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?></div>
            <?php endif; ?>

            <form method="POST" action="admin.php" class="modern-form">
                <input type="hidden" name="action" value="add">

                <div class="form-section">
                    <h3><i class="fas fa-industry"></i> Linha de Produção</h3>
                    <div class="form-group full-width">
                        <label for="tipo">Tipo do Produto (Factory):</label>
                        <select id="tipo" name="tipo" required class="input-modern">
                            <option value="" disabled selected>Selecione o tipo de item...</option>
                            <option value="figure">Action Figure / Estatueta</option>
                            <option value="plush">Pelúcia</option>
                            <option value="clothing">Vestuário (Roupas)</option>
                            <option value="accessory">Acessório (Chaveiro/Pin)</option>
                        </select>
                        <small style="color: #666; margin-top: 5px; display: block;">Isso define a categoria automaticamente usando o padrão Factory.</small>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-info-circle"></i> Detalhes do Item</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nome">Nome do Produto:</label>
                            <input type="text" id="nome" name="nome" placeholder="Ex: Pelúcia Pomni" required class="input-modern">
                        </div>
                        
                        <div class="form-group">
                            <label for="preco">Preço (R$):</label>
                            <input type="number" id="preco" name="preco" step="0.01" min="0" placeholder="0.00" required class="input-modern">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="desconto">Desconto (%):</label>
                            <input type="number" id="desconto" name="desconto" step="1" min="0" max="100" value="0" class="input-modern">
                        </div>
                        
                        <div class="form-group">
                            <label for="imagem">URL da Imagem:</label>
                            <input type="text" id="imagem" name="imagem" placeholder="./imgs/exemplo.png" required class="input-modern">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="descricao">Descrição Detalhada:</label>
                        <textarea id="descricao" name="descricao" rows="4" required class="input-modern"></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="admin.php" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary btn-lg">
                        <i class="fas fa-check-circle"></i> Fabricar e Salvar
                    </button>
                </div>
            </form>
        </div>
    </main>
    
    <script src="assets/js/admin.js"></script>
</body>
</html>