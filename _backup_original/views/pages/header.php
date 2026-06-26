<?php
require_once __DIR__ . "/../../backend/sistema.php";
$sistema = Sistema::getInstancia();
$usuario = $sistema->obterUsuarioLogado();
?>
<header class="header-top">
    <h2 class="welcome-text">
        <?php if ($usuario): ?>
            SEJA BEM VINDO, <?= htmlspecialchars($usuario->nome) ?>
        <?php else: ?>
            SEJA BEM VINDO
        <?php endif; ?>
    </h2>

    <div class="header-actions">
        <div class="search-bar">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" placeholder="PESQUISAR..." class="search-input">
        </div>
        <div class="user-avatar">
            <?php if ($usuario && !empty($usuario->avatar ?? "")): ?>
                <img src="<?= htmlspecialchars($usuario->avatar) ?>" alt="User Avatar">
            <?php else: ?>
                <img src="../image\perfil.png">
                    alt="User Avatar">
            <?php endif; ?>
        </div>
    </div>
</header>
