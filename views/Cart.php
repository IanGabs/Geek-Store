<?php include __DIR__ . '/layout/header.php'; ?>

<main>
    <section class="carrinho">
        <h1>Seu Carrinho</h1>
        
        <?php if (empty($itens_carrinho)): ?>
            <div class="carrinho-vazio">
                <p>Seu carrinho está vazio.</p>
                <a href="produtos.php" class="btn-primary">Continuar Comprando</a>
            </div>
        <?php else: ?>
            <div class="itens-carrinho">
                <?php foreach ($itens_carrinho as $item): ?>
                    <div class="item-carrinho">
                        <img src="<?php echo $item['imagem']; ?>" alt="<?php echo $item['nome']; ?>">
                        <div class="item-detalhes">
                            <h3><?php echo $item['nome']; ?></h3>
                            <p>Preço: R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                            <div class="quantidade">
                                <button class="btn-diminuir" data-produto-id="<?php echo $item['produto_id']; ?>">-</button>
                                <span><?php echo $item['quantidade']; ?></span>
                                <button class="btn-aumentar" data-produto-id="<?php echo $item['produto_id']; ?>">+</button>
                            </div>
                            <p>Subtotal: R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></p>
                            <button class="btn-remover" data-produto-id="<?php echo $item['produto_id']; ?>">Remover</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="resumo-carrinho">
                <h2>Resumo do Pedido</h2>
                <p>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></p>
                <a href="#" class="btn-primary" onclick="alert('Funcionalidade de finalizar compra em desenvolvimento!'); return false;">Finalizar Compra</a>
            </div>
        <?php endif; ?>
    </section>
</main>

<script src="assets/js/carrinho.js"></script>
<?php include __DIR__ . '/layout/footer.php'; ?>