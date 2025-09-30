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

    <main style="max-width: 1400px; margin: 0 auto; padding: 2rem;">
        <section style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 12px rgba(94, 53, 177, 0.15);">
            
            <!-- TÍTULO PRINCIPAL -->
            <h1 style="text-align: center; color: var(--cor-primaria); margin-bottom: 2rem; font-size: 2.5rem;">
                <i class="fas fa-shield-alt"></i> Painel Administrativo
            </h1>

            <!-- MENSAGENS -->
            <?php if (isset($_SESSION['admin_message'])): ?>
                <div class="success-message"><?php echo $_SESSION['admin_message']; unset($_SESSION['admin_message']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['admin_error'])): ?>
                <div class="error-message"><?php echo $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?></div>
            <?php endif; ?>

            <!-- ========== SEÇÃO DE EXPORTAÇÃO ========== -->
            <div style="background: linear-gradient(135deg, #f0f4ff, #e3f2fd); padding: 2.5rem; border-radius: 16px; margin: 2rem 0; text-align: center; border: 2px solid #e1e8f0;">
                <h2 style="color: var(--cor-primaria); margin-bottom: 1rem; font-size: 1.8rem;">
                    <i class="fas fa-download"></i> Exportar Dados do Sistema
                </h2>
                <p style="color: #666; margin-bottom: 1.5rem; font-size: 1.1rem;">
                    Baixe a lista completa de <?php echo count($produtos); ?> produtos cadastrados
                </p>
                <div style="display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap;">
                    <a href="export.php?format=json" style="display: inline-flex; align-items: center; gap: 10px; padding: 15px 35px; background: linear-gradient(to right, #f39c12, #e67e22); color: white; text-decoration: none; border-radius: 30px; font-weight: 600; transition: all 0.3s; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); font-size: 1.1rem;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 8px rgba(0, 0, 0, 0.2)'">
                        <i class="fas fa-file-code" style="font-size: 1.3rem;"></i> Exportar JSON
                    </a>
                    <a href="export.php?format=csv" style="display: inline-flex; align-items: center; gap: 10px; padding: 15px 35px; background: linear-gradient(to right, #27ae60, #229954); color: white; text-decoration: none; border-radius: 30px; font-weight: 600; transition: all 0.3s; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); font-size: 1.1rem;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 8px rgba(0, 0, 0, 0.2)'">
                        <i class="fas fa-file-csv" style="font-size: 1.3rem;"></i> Exportar CSV
                    </a>
                </div>
            </div>

            <!-- ========== ADICIONAR PRODUTO ========== -->
            <div style="text-align: center; margin: 3rem 0;">
                <button class="btn-primary" onclick="toggleForm()" style="font-size: 1.1rem; padding: 15px 35px;">
                    <i class="fas fa-plus-circle"></i> Adicionar Novo Produto
                </button>
            </div>

            <!-- FORMULÁRIO (OCULTO) -->
            <div id="add-product-form" style="display: none; background: #f8f9fa; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; border: 2px solid #e1e8f0;">
                <h2 style="color: var(--cor-primaria); margin-bottom: 1.5rem;">Adicionar Novo Produto</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="add">

                    <div class="form-group">
                        <label for="nome">Nome do Produto:</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" name="descricao" required style="width: 100%; padding: 12px; border-radius: 8px; border: 2px solid #e1e8f0; min-height: 100px; font-family: inherit;"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="preco">Preço (R$):</label>
                        <input type="number" id="preco" name="preco" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="imagem">URL da Imagem:</label>
                        <input type="text" id="imagem" name="imagem" placeholder="./imgs/produto.png" required>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-check"></i> Adicionar Produto
                        </button>
                        <button type="button" class="btn-secondary" onclick="toggleForm()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>

            <!-- ========== LISTA DE PRODUTOS ========== -->
            <div>
                <h2 style="text-align: center; color: var(--cor-primaria); margin: 3rem 0 2rem 0; font-size: 2rem;">
                    <i class="fas fa-box-open"></i> Produtos Cadastrados (<?php echo count($produtos); ?>)
                </h2>
                <div class="produtos-grid">
                    <?php foreach($produtos as $produto): ?>
                        <div class="produto-card">
                            <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                            <h3><?php echo $produto['nome']; ?></h3>
                            <p><?php echo $produto['descricao']; ?></p>
                            <p style="font-size: 1.4rem; font-weight: bold; color: var(--cor-destaque); margin: 1rem 0;">
                                R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                            </p>

                            <form method="POST" style="margin-top: 1rem;" onsubmit="return confirm('Tem certeza que deseja remover este produto?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="product_id" value="<?php echo $produto['id']; ?>">
                                <button type="submit" class="btn-remover" style="width: 100%; padding: 12px; background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: 600; font-size: 1rem;">
                                    <i class="fas fa-trash-alt"></i> Remover Produto
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <script>
        function toggleForm() {
            const form = document.getElementById('add-product-form');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
                form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</body>
</html>