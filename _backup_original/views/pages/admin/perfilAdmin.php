<?php
require_once "../../../backend/sistema.php";
require_once "../../../backend/protegerAdmin.php";

$sistema = Sistema::getInstancia();
$usuario = $sistema->obterUsuarioLogado();
if (!$usuario) {
    header("Location: ../../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>GlamourTime - Perfil da Manicure</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="stylesheet" href="../../css/perfil.css">
    <link rel="stylesheet" href="../../css/components.css">
</head>
<body>

    <?php include "sidebarAdmin.php"; ?>

    <main class="main-content">

        <header class="header-top">
            <h1 class="page-title">PERFIL DA MANICURE</h1>

            <div class="header-actions">
                <div class="search-bar">
                    <input type="text" placeholder="PESQUISAR ........." class="search-input">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <div class="user-avatar-small">
                    <img src="https://img.freepik.com/premium-photo/3d-avatar-cartoon-character_113255-93278.jpg" alt="Admin Avatar">
                </div>
            </div>
        </header>

        <?php if (isset($_GET["atualizado"])): ?>
            <script>alert("Perfil atualizado com sucesso!");</script>
        <?php endif; ?>

        <section class="profile-container">
            <section class="avatar-section">
                <img src="../../image/perfil.png" alt="Avatar 3D do Usuário" class="avatar-img">
                <button class="edit-btn" aria-label="Editar Foto de Perfil" title="Editar Foto"></button>
            </section>

            <section class="profile-card">
                <form action="../../../backend/atualizarPerfil.php" method="POST">
                    <section class="form-group">
                        <label for="nome" class="form-label">NOME:</label>
                        <input type="text" name="nome" id="nome" class="form-input" value="<?= htmlspecialchars($usuario->nome) ?>">
                    </section>

                    <section class="form-group">
                        <label for="email" class="form-label">E-MAIL:</label>
                        <input type="email" name="email" id="email" class="form-input" value="<?= htmlspecialchars($usuario->email) ?>">
                    </section>

                    <section class="form-group">
                        <label for="telefone" class="form-label">TELEFONE</label>
                        <input type="tel" name="telefone" id="telefone" class="form-input" value="<?= htmlspecialchars($usuario->telefone ?? '') ?>">
                    </section>

                    <button type="submit" class="btn-action btn-save">SALVAR ALTERAÇÕES</button>
                </form>
            </section>
        </section>

    </main>

</body>
</html>
