<?php include __DIR__ . '/layout/header.php'; ?>

<main>
    <?php 
    // MODIFICAÇÃO: Verifica se o usuário é admin para mostrar o formulário
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): 
    ?>
        <section class="admin-section" style="background-color: #fff; padding: 2rem; margin: 0 2rem 2rem 2rem; border-radius: 12px; box-shadow: var(--sombra);">
            <div class="admin-actions">
                <h1>Painel do Administrador</h1>
                
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="success-message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <h2 style="text-align: left; padding-bottom: 1rem;">Adicionar Novo Produto</h2>
            </div>
            
            <div id="add-product-form" class="admin-form">
                <form method="POST" action="index.php">
                    <input type="hidden" name="action" value="add_product">
                    
                    <div class="form-group">
                        <label for="nome">Nome do Produto:</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" name="descricao" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;"></textarea>
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
                    </div>
                </form>
            </div>
        </section>
    <?php endif; ?>

    <section class="banner">
        <h1>Bem-vindo à Tecny Geek Store</h1>
        <p>Encontre os produtos dos seus personagens favoritos!</p>
        <a href="produtos.php" class="btn-primary">Ver Produtos</a>
    </section>

    <section class="destaques">
        <h2>Produtos em Destaque</h2>
        <div class="produtos-grid">
            <?php foreach($produtos as $produto): ?>
                <div class="produto-card">
                    <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                    <h3><?php echo $produto['nome']; ?></h3>
                    <p>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                    <button class="btn-adicionar-carrinho" data-produto-id="<?php echo $produto['id']; ?>">
                        Adicionar ao Carrinho
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/layout/footer.php'; ?>