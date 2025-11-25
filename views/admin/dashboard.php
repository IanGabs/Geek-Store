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

    <main style="max-width: 1400px; margin: 0 auto; padding: 2rem;">
        <section class="admin-panel">
            
            <h1 style="text-align: center; color: var(--cor-primaria); margin-bottom: 2rem; font-size: 2.5rem;">
                <i class="fas fa-tachometer-alt"></i> Dashboard Administrativo
            </h1>

            <?php if (isset($_SESSION['admin_message'])): ?>
                <div class="success-message"><?php echo $_SESSION['admin_message']; unset($_SESSION['admin_message']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['admin_error'])): ?>
                <div class="error-message"><?php echo $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?></div>
            <?php endif; ?>

            <div class="dashboard-actions" style="display: flex; gap: 20px; justify-content: center; margin-bottom: 3rem;">
                <a href="admin.php?page=new" class="btn-primary btn-lg">
                    <i class="fas fa-plus-circle"></i> Novo Produto
                </a>
            </div>

            <div class="export-section" style="background: linear-gradient(135deg, #f0f4ff, #e3f2fd); padding: 2rem; border-radius: 16px; margin-bottom: 3rem; text-align: center; border: 1px solid #d1d9e6;">
                <h3 style="color: var(--cor-primaria); margin-bottom: 1rem;">Exportar Catálogo</h3>
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a href="export.php?format=json" class="btn-secondary"><i class="fas fa-file-code"></i> Baixar JSON</a>
                    <a href="export.php?format=csv" class="btn-secondary"><i class="fas fa-file-csv"></i> Baixar CSV</a>
                </div>
            </div>

            <div>
                <h2 style="text-align: center; color: var(--cor-primaria); margin-bottom: 2rem;">
                    <i class="fas fa-boxes"></i> Produtos Ativos (<?php echo count($produtos); ?>)
                </h2>
                <div class="produtos-grid">
                    <?php foreach($produtos as $produto): ?>
                        <div class="produto-card">
                            <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                            <h3><?php echo $produto['nome']; ?></h3>
                            
                            <?php if(isset($produto['categoria']) && !empty($produto['categoria'])): ?>
                                <span class="badge-categoria"><?php echo $produto['categoria']; ?></span>
                            <?php endif; ?>

                            <p style="font-size: 1.4rem; font-weight: bold; color: var(--cor-destaque); margin: 1rem 0;">
                                R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                            </p>

                            <form method="POST" action="admin.php" onsubmit="return confirm('Tem certeza que deseja remover este produto?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="product_id" value="<?php echo $produto['id']; ?>">
                                <button type="submit" class="btn-remover" style="width: 100%;">
                                    <i class="fas fa-trash-alt"></i> Remover
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <script src="assets/js/admin.js"></script>
</body>
</html>