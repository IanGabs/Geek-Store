<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Tecny Geek Store'; ?></title>
    <link rel="icon" href="./imgs/Tecny__1_-removebg-preview.png" type="image/png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                <li>
                    <a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active-link' : ''; ?>">
                        Início
                    </a>
                </li>
                <li>
                    <a href="produtos.php" class="<?php echo ($current_page == 'produtos.php') ? 'active-link' : ''; ?>">
                        Produtos
                    </a>
                </li>
                <li>
                    <a href="carrinho.php" class="<?php echo ($current_page == 'carrinho.php') ? 'active-link' : ''; ?>">
                        <i class="fas fa-shopping-cart"></i> Carrinho 
                        <span class="carrinho-contador"><?php echo isset($totalItensCarrinho) ? $totalItensCarrinho : 0; ?></span>
                    </a>
                </li>

                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    
                    <?php if ($_SESSION['user_type'] === 'admin'): ?>
                        <li><a href="admin.php" class="<?php echo (!isset($_GET['page']) && $current_page == 'admin.php') ? 'active-link' : ''; ?>"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                        <li><a href="admin.php?page=new" class="<?php echo (isset($_GET['page']) && $_GET['page'] === 'new') ? 'active-link' : ''; ?>"><i class="fas fa-plus"></i> Novo Produto</a></li>
                    <?php endif; ?>

                    <li class="profile-menu-container">
                        <div class="profile-trigger" id="profileTrigger" title="Meu Perfil">
                            <?php
                            $foto_header = isset($_SESSION['user_photo']) && !empty($_SESSION['user_photo']) 
                            ? $_SESSION['user_photo'] 
                            : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
                            ?>
                            <img src="<?php echo $foto_header; ?>" alt="Perfil" class="profile-img">
                        </div>
                        
                        <div class="dropdown-menu" id="profileDropdown">
                            <div class="user-info-header">
                                <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
                                <small><?php echo $_SESSION['user_type'] === 'admin' ? 'Administrador' : 'Cliente'; ?></small>
                            </div>
                            <hr>
                            
                            <a href="profile.php"><i class="fas fa-cog"></i> Configurações</a>
                            <a href="orders.php"><i class="fas fa-history"></i> Histórico de Compras</a>
                            
                            <hr>
                            <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Sair</a>
                        </div>
                    </li>

                <?php else: ?>
                    
                    <li><a href="login.php" class="<?php echo ($current_page == 'login.php') ? 'active-link' : ''; ?>"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                
                <?php endif; ?>
            </ul>
        </nav>
    </header>