<?php include __DIR__ . '/layout/header.php'; ?>

<main>
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
                <a href="detalhes.php?id=<?php echo $produto['id']; ?>" style="text-decoration: none; color: inherit;">
                    <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                    <h3><?php echo $produto['nome']; ?></h3>
                </a>
                
                <?php if ($produto['desconto'] > 0): ?>
                <p>
                    <span style="text-decoration: line-through; color: #999;">
                        R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                    </span>
                <br>
                <strong style="color: var(--cor-destaque);">
                    R$ <?php echo number_format($produto['preco_final'], 2, ',', '.'); ?>
                </strong>
                </p>
            <?php else: ?>
                <p>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
            <?php endif; ?>
            
            <p><?php echo $produto['descricao']; ?></p>
            
            <button class="btn-adicionar-carrinho" data-produto-id="<?php echo $produto['id']; ?>">
                Adicionar ao Carrinho
            </button>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/layout/footer.php'; ?>