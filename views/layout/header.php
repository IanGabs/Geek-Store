<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Tecny Geek Store'; ?></title>
    <link rel="icon" href="./imgs/Tecny__1_-removebg-preview.png" type="image/png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .nav-text span {
            color: white;
            font-weight: 500;
            padding: 8px 12px;
            display: inline-block;
        }
        /* Estilo para aba ativa */
        .active-tab {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php">
                <img src="./imgs/Tecny-removebg-preview.png" alt="Tecny Geek Store">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="produtos.php">Produtos</a></li>
                <li><a href="carrinho.php">
                    <i class="fas fa-shopping-cart"></i> Carrinho 
                    <span class="carrinho-contador"><?php echo isset($totalItensCarrinho) ? $totalItensCarrinho : 0; ?></span>
                </a></li>

                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    
                    <li class="nav-text"><span>Olá, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span></li>
                    
                    <?php if ($_SESSION['user_type'] === 'admin'): ?>
                        <li><a href="admin.php" class="<?php echo (!isset($_GET['page'])) ? 'active-tab' : ''; ?>"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                        <li><a href="admin.php?page=new" class="<?php echo (isset($_GET['page']) && $_GET['page'] === 'new') ? 'active-tab' : ''; ?>"><i class="fas fa-plus"></i> Novo Produto</a></li>
                    <?php endif; ?>
                    
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>

                <?php else: ?>
                    
                    <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                
                <?php endif; ?>
            </ul>
        </nav>
    </header>