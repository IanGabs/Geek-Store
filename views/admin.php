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
    <header>
        <div class="logo">
            <img src="./imgs/Tecny-removebg-preview.png" alt="Tecny Geek Store">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Ver Site</a></li>
                <li><a href="admin.php"><i class="fas fa-cog"></i> Admin</a></li>
                <li><span class="user-welcome">Olá, <?php echo $user_name; ?>!</span></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
            </ul>
        </nav>
    </header>

    <main class="admin-main">
        <section class="admin-section">
            <h1>Painel Administrativo</h1>

            <?php if (isset($_SESSION['admin_message'])): ?>
                <div class="success-message"><?php echo $_SESSION['admin_message']; unset($_SESSION['admin_message']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['admin_error'])): ?>
                <div class="error-message"><?php echo $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?></div>
            <?php endif; ?>

            <div class="admin-actions">
                <button class="btn-primary" onclick="toggleForm()">
                    <i class="fas fa-plus"></i> Adicionar Produto
                </button>

                <div class="admin-exports" style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: center;">
                    <a href="export.php?format=json" class="btn-secondary" style="background: linear-gradient(to right, #26a69a, #00acc1);"><i class="fas fa-file-code"></i> Baixar JSON</a>
                    <a href="export.php?format=csv" class="btn-secondary" style="background: linear-gradient(to right, #66bb6a, #43a047);"><i class="fas fa-file-csv"></i> Baixar CSV</a>
                </div>
            </div>

            <div id="add-product-form" class="admin-form" style="display: none;">
                <h2>Adicionar Novo Produto</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="add">

                    <div class="form-group">
                        <label for="nome">Nome do Produto:</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" name="descricao" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="preco">Preço (R$):</label>
                        <input type="number" id="preco" name="preco" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="imagem">URL da Imagem:</label>
                        <input type="text" id="imagem" name="imagem" placeholder="./imgs/produto.png" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Adicionar Produto</button>
                        <button type="button" class="btn-secondary" onclick="toggleForm()">Cancelar</button>
                    </div>
                </form>
            </div>

            <div class="products-section">
                <h2>Produtos Cadastrados</h2>
                <div class="produtos-grid">
                    <?php foreach($produtos as $produto): ?>
                        <div class="produto-card admin-produto-card">
                            <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                            <h3><?php echo $produto['nome']; ?></h3>
                            <p><?php echo $produto['descricao']; ?></p>
                            <p class="produto-preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>

                            <div class="admin-actions">
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja remover este produto?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="product_id" value="<?php echo $produto['id']; ?>">
                                    <button type="submit" class="btn-danger">
                                        <i class="fas fa-trash"></i> Remover
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <script src="assets/js/admin.js"></script>
</body>
</html>