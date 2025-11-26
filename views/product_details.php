<?php include __DIR__ . '/layout/header.php'; ?>

<main class="product-page-container">
    
    <?php if (!empty($msg_sucesso)): ?>
        <div class="success-message"><?php echo $msg_sucesso; ?></div>
    <?php endif; ?>
    <?php if (!empty($msg_erro)): ?>
        <div class="error-message"><?php echo $msg_erro; ?></div>
    <?php endif; ?>

    <div class="product-detail-grid">
        <div class="product-gallery">
            <div class="main-image-frame">
                <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" id="mainImg">
            </div>
        </div>

        <div class="product-info-block">
            <h1 class="product-title"><?php echo $produto['nome']; ?></h1>
            
            <div class="rating-summary">
                <span class="stars">
                    <?php for($i=1; $i<=5; $i++) echo ($i <= round($media_nota)) ? '★' : '☆'; ?>
                </span>
                <span class="rating-text"><?php echo $total_reviews; ?> Reviews</span>
            </div>

            <div class="price-block">
                <?php if ($produto['desconto'] > 0): ?>
                    <span class="old-price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
                    <span class="discount-badge"><?php echo intval($produto['desconto']); ?>% OFF</span>
                <?php endif; ?>
                <div class="current-price">R$ <?php echo number_format($produto['preco_final'], 2, ',', '.'); ?></div>
            </div>

            <p class="product-desc"><?php echo nl2br($produto['descricao']); ?></p>

            <div class="specs-list">
                <div class="spec-item"><strong>Categoria:</strong> <?php echo isset($produto['categoria']) ? $produto['categoria'] : 'Geral'; ?></div>
                <div class="spec-item"><strong>Envio:</strong> 1 a 3 dias úteis</div>
            </div>

            <div class="action-area">
                <button class="btn-add-cart-large btn-adicionar-carrinho" data-produto-id="<?php echo $produto['id']; ?>">
                    ADICIONAR AO CARRINHO - R$ <?php echo number_format($produto['preco_final'], 2, ',', '.'); ?>
                </button>
            </div>
        </div>
    </div>

    <section class="reviews-section">
        <h2><i class="fas fa-comments"></i> Avaliações dos Clientes</h2>
        
        <?php if (isset($_SESSION['logged_in'])): ?>
            <div class="review-form-box">
                <h3>Deixe sua opinião</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="avaliar">
                    <div class="star-input">
                        <input type="radio" name="nota" id="star5" value="5" required/><label for="star5">★</label>
                        <input type="radio" name="nota" id="star4" value="4" /><label for="star4">★</label>
                        <input type="radio" name="nota" id="star3" value="3" /><label for="star3">★</label>
                        <input type="radio" name="nota" id="star2" value="2" /><label for="star2">★</label>
                        <input type="radio" name="nota" id="star1" value="1" /><label for="star1">★</label>
                        <span>Sua nota:</span>
                    </div>
                    <textarea name="comentario" placeholder="O que você achou deste produto?" required></textarea>
                    <button type="submit" class="btn-primary">Enviar Avaliação</button>
                </form>
            </div>
        <?php else: ?>
            <div class="login-to-review">
                <p>Faça <a href="login.php">login</a> para deixar uma avaliação.</p>
            </div>
        <?php endif; ?>

        <div class="reviews-list">
            <?php if (empty($reviews)): ?>
                <p class="no-reviews">Ainda não há avaliações. Seja o primeiro!</p>
            <?php else: ?>
                <?php foreach($reviews as $review): ?>
                    <div class="review-card">
                        
                        <div class="review-avatar">
                            <?php 
                                $foto_user = !empty($review['foto_perfil']) 
                                    ? $review['foto_perfil'] 
                                    : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
                            ?>
                            <img src="<?php echo $foto_user; ?>" alt="Foto de <?php echo htmlspecialchars($review['usuario_nome']); ?>">
                        </div>

                        <div class="review-content">
                            <div class="review-header">
                                <strong><?php echo htmlspecialchars($review['usuario_nome']); ?></strong>
                                <div class="review-stars">
                                    <?php for($i=1; $i<=5; $i++) echo ($i <= $review['nota']) ? '★' : '☆'; ?>
                                </div>
                            </div>
                            <p class="review-date">Avaliado em <?php echo date('d/m/Y', strtotime($review['created_at'])); ?></p>
                            <p class="review-body"><?php echo htmlspecialchars($review['comentario']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php include __DIR__ . '/layout/footer.php'; ?>