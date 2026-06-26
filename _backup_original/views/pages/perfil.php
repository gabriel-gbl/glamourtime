<?php
require_once __DIR__ . "/../../backend/sistema.php";
$sistema = Sistema::getInstancia();
$usuario = $sistema->obterUsuarioLogado();
if (!$usuario) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>GlamourTime - Perfil do Usuário</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="stylesheet" href="../css/components.css">
</head>
<body>
    <?php include "sidebar.php"; ?>
    <main class="main-content">
        <?php include "header.php"; ?>
        <?php if (isset($_GET["atualizado"])): ?>
            <script>
                alert("Perfil atualizado com sucesso!");
            </script>
        <?php endif; ?>


        <section class="profile-container">
            <section class="avatar-section">
                <img src="../image/perfil.png" alt="Avatar 3D do Usuário" class="avatar-img">
                <button class="edit-btn" aria-label="Editar Foto de Perfil" title="Editar Foto">
                </button>
            </section>

            <section class="profile-card">
                <form action="../../backend/atualizarPerfil.php" method="POST">
                    <section class="form-group">
                        <label for="nome" class="form-label">NOME:</label>
                        <input type="text" name="nome" id="nome" class="form-input" value="<?= htmlspecialchars($usuario->nome) ?>" placeholder="Ex: Maria Eduarda">
                    </section>

                    <section class="form-group">
                        <label for="email" class="form-label">E-MAIL:</label>
                        <input type="email" name="email" id="email" class="form-input" value="<?= htmlspecialchars($usuario->email) ?>" placeholder="Ex: maria@gmail.com">
                    </section>

                    <section class="form-group">
                        <label for="telefone" class="form-label">TELEFONE</label>
                        <input type="tel" name="telefone" id="telefone" class="form-input" value="<?= htmlspecialchars($usuario->telefone ?? '') ?>" placeholder="Ex: 11 999999999">
                    </section>
                    
                    <button type="submit" class="btn-action btn-save">SALVAR ALTERAÇÕES</button>
                </form>              
                <a href="../../backend/excluirConta.php">
                    <button type="button" class="btn-delete-account" style="
                        background:#ff3b3b;
                        color:#fff;
                        padding:12px 18px;
                        border:none;
                        border-radius:8px;
                        font-size:1rem;
                        cursor:pointer;
                        margin-top:20px;
                        width:100%;
                    ">
                        EXCLUIR MINHA CONTA
                    </button>
                </a>

            </section>
        </section>
    </main>
</body>
</html>
