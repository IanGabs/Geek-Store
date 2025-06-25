<?php include __DIR__ . '/layout/header.php'; ?>

<main>
    <section class="produtos-catalogo">
        <h1>Nossos Produtos</h1>
        <div class="produtos-grid">
            <?php foreach($produtos as $produto): ?>
                <div class="produto-card">
                    <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                    <h3><?php echo $produto['nome']; ?></h3>
                    <p>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
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