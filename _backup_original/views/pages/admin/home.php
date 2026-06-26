<?php
require_once "../../../backend/sistema.php";
require_once "../../../backend/protegerAdmin.php";


$sistema = Sistema::getInstancia();
$usuario = $sistema->obterUsuarioLogado();

$agendamentos = $sistema->listarAgendamentosManicure();

$valorServico = 40;

$totalPendentes = 0;
$totalConcluidos = 0;

foreach ($agendamentos as $a) {
    if ($a->status === "pendente") $totalPendentes++;
    if ($a->status === "concluido") $totalConcluidos++;
}

$receitaTotal = $totalConcluidos * $valorServico;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlamourTime - Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/components.css">
    <link rel="stylesheet" href="../../css/homeAdmin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include "sidebarAdmin.php"; ?>

    <main class="main-content">
        
        <header class="header-top">
            <h1 class="page-title">BEM VINDO, <?= htmlspecialchars($usuario->nome) ?></h1>
            
            <div class="header-actions">
                <div class="search-bar">
                    <input type="text" placeholder="PESQUISAR ........." class="search-input">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>

                <div class="user-avatar-small">
                    <img src="https://img.freepik.com/premium-photo/3d-avatar-cartoon-character_113255-93278.jpg" alt="Admin Avatar">
                </div>
            </div>
        </header>

        <section class="banner">
            <svg class="pin-icon" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                <path d="M16,12V4H17V2H7V4H8V12L6,14V16H11V22H13V16H18V14L16,12Z" fill="#3B82F6"></path>
            </svg>

            <div class="banner-img-container">
                <img src="../../image/pessoa-atendimento.png" alt="Admin Illustration" class="banner-img">
            </div>
            
            <div class="banner-content">
                <h2 class="banner-title">SEJA BEM VINDA,<br><?= htmlspecialchars($usuario->nome) ?></h2>

                <div style="margin-bottom: 1rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#FFF"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14" stroke="#7C3AED" stroke-width="2" fill="none"></polyline></svg>
                </div>

                <p class="banner-text">
                    Acompanhe seus atendimentos, horários e desempenho do dia em um clique.
                </p>
                
                <button class="banner-btn" onclick="window.location.href='gerenciarAtendimentos.php'">GERENCIAR AGORA</button>
            </div>
        </section>

        <section class="bottom-section">

            <div class="info-card">
                <div class="profile-container">
                    <img src="https://img.freepik.com/premium-photo/3d-avatar-cartoon-character_113255-93278.jpg" alt="Admin" class="profile-pic-large">

                    <div class="profile-details">
                        <h3><?= htmlspecialchars($usuario->nome) ?></h3>
                        <span class="role">Manicure</span>

                        <div class="email">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <?= htmlspecialchars($usuario->email) ?>
                        </div>

                        <button class="edit-profile-btn" onclick="window.location.href='../../pages/admin/perfilAdmin.php'">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                            Editar Perfil
                        </button>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header">
                    <h4 class="card-title-sm">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Resumo do Dia
                    </h4>
                </div>

                <div class="stats-grid">

                    <div class="stat-item">
                        <svg class="stat-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span class="stat-number"><?= $totalPendentes ?></span>
                        <span class="stat-label">Pendentes</span>
                    </div>

                    <div class="stat-item">
                        <svg class="stat-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                        </svg>
                        <span class="stat-number"><?= $totalConcluidos ?></span>
                        <span class="stat-label">Atendidos</span>
                    </div>

                    <div class="stat-item">
                        <svg class="stat-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        <span class="stat-number">R$ <?= number_format($receitaTotal, 2, ',', '.') ?></span>
                        <span class="stat-label">Receita</span>
                    </div>

                </div>
            </div>

        </section>

    </main>

</body>
</html>
