<?php include __DIR__ . '/layout/header.php'; ?>

<main class="profile-container">
    <div class="profile-header-banner">
        <h1>Configurações da Conta</h1>
        <p>Gerencie seus dados pessoais e preferências.</p>
    </div>

    <div class="profile-grid">
        <aside class="profile-sidebar">
            <div class="profile-card photo-card">
                <div class="current-photo">
                    <?php 
                        $foto = !empty($user['foto_perfil']) ? $user['foto_perfil'] : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
                    ?>
                    <img src="<?php echo $foto; ?>" alt="Foto de Perfil" id="preview-avatar">
                </div>
                <h3><?php echo htmlspecialchars($user['nome']); ?></h3>
                <p class="member-since">Membro desde <?php echo date('Y', strtotime($user['created_at'])); ?></p>
                
                <form method="POST" enctype="multipart/form-data" class="upload-form">
                    <input type="hidden" name="action" value="upload_photo">
                    <label for="foto-upload" class="btn-secondary btn-sm">
                        <i class="fas fa-camera"></i> Alterar Foto
                    </label>
                    <input type="file" id="foto-upload" name="foto" accept="image/*" onchange="this.form.submit()" style="display: none;">
                </form>
            </div>

            <nav class="profile-nav">
                <a href="orders.php" class="nav-item">
                    <i class="fas fa-shopping-bag"></i> Meus Pedidos
                </a>
                <a href="#" class="nav-item active">
                    <i class="fas fa-user-cog"></i> Dados Pessoais
                </a>
                <a href="logout.php" class="nav-item text-danger">
                    <i class="fas fa-sign-out-alt"></i> Sair da Conta
                </a>
            </nav>
        </aside>

        <section class="profile-content">
            
            <?php if (!empty($msg_sucesso)): ?>
                <div class="success-message"><?php echo $msg_sucesso; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($msg_erro)): ?>
                <div class="error-message"><?php echo $msg_erro; ?></div>
            <?php endif; ?>

            <div class="profile-card">
                <div class="card-header">
                    <h2><i class="fas fa-user-edit"></i> Informações Pessoais</h2>
                </div>
                <form method="POST" class="modern-form">
                    <input type="hidden" name="action" value="update_info">
                    
                    <div class="form-group">
                        <label>Nome Completo</label>
                        <input type="text" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required class="input-modern">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="input-modern">
                    </div>

                    <div class="form-actions right">
                        <button type="submit" class="btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>

            <div class="profile-card">
                <div class="card-header">
                    <h2><i class="fas fa-lock"></i> Segurança</h2>
                </div>
                <form method="POST" class="modern-form">
                    <input type="hidden" name="action" value="update_password">
                    
                    <div class="form-group">
                        <label>Senha Atual</label>
                        <input type="password" name="senha_atual" required class="input-modern">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Nova Senha</label>
                            <input type="password" name="nova_senha" required minlength="6" class="input-modern">
                        </div>
                        <div class="form-group">
                            <label>Confirmar Nova Senha</label>
                            <input type="password" name="confirmar_senha" required minlength="6" class="input-modern">
                        </div>
                    </div>

                    <div class="form-actions right">
                        <button type="submit" class="btn-secondary">Alterar Senha</button>
                    </div>
                </form>
            </div>

        </section>
    </div>
</main>

<?php include __DIR__ . '/layout/footer.php'; ?>