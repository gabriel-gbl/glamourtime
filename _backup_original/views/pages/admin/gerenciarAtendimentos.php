<?php
require_once "../../../backend/sistema.php";
require_once "../../../backend/protegerAdmin.php";

$sistema = Sistema::getInstancia();
$usuario = $sistema->obterUsuarioLogado();

$agendamentos = $sistema->listarAgendamentosManicure();

$pendentes = [];
$confirmados = [];
$concluidos = [];
$cancelados = [];

foreach ($agendamentos as $a) {
    switch ($a->status) {
        case "pendente": $pendentes[] = $a; break;
        case "confirmado": $confirmados[] = $a; break;
        case "concluido": $concluidos[] = $a; break;
        case "cancelado": $cancelados[] = $a; break;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlamourTime - Admin Agendamentos</title>
    <link rel="stylesheet" href="../../css/components.css">
    <link rel="stylesheet" href="../../css/gerenciarAtedimentos.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include "sidebarAdmin.php"; ?>

<main class="main-content">

    <header class="header-top">
        <h1 class="page-title">GERENCIAR ATENDIMENTOS</h1>

        <div class="header-actions">
            <div class="search-bar">
                <input type="text" placeholder="PESQUISAR ........." class="search-input">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <div class="user-avatar-small">
                <img src="https://img.freepik.com/premium-photo/3d-avatar-cartoon-character_113255-93278.jpg">
            </div>
        </div>
    </header>

    <h2 class="section-subtitle">PENDENTES</h2>
    <div class="appointments-container">
        <?php if (empty($pendentes)): ?>
            <p class="empty-msg">Nenhum agendamento pendente.</p>
        <?php else: ?>
            <?php foreach ($pendentes as $a): ?>
                <div class="appointment-card">

                    <div class="action-block">
                        <div class="date-display">
                            <?= date("d/m/Y", strtotime($a->data)) ?> às <?= $a->hora ?>
                        </div>

                        <a href="../../../backend/confirmar.php?id=<?= $a->id ?>">
                            <button class="confirm-btn">CONFIRMAR</button>
                        </a>

                        <a href="../../../backend/rejeitar.php?id=<?= $a->id ?>">
                            <button class="cancel-btn">REJEITAR</button>
                        </a>
                    </div>

                    <div class="appointment-info">
                        <p><b>Cliente:</b> <?= $sistema->usuarios[$a->idCliente - 1]->nome ?></p>
                        <p><b>Serviço:</b> <?= $a->servico ?></p>
                    </div>

                    <div class="illustration-container">
                        <img src="../../image/pessoa-agendar.png" class="illustration-img">
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <h2 class="section-subtitle">CONFIRMADOS</h2>
    <div class="appointments-container">
        <?php if (empty($confirmados)): ?>
            <p class="empty-msg">Nenhum agendamento confirmado.</p>
        <?php else: ?>
            <?php foreach ($confirmados as $a): ?>
                <div class="appointment-card">

                    <div class="action-block">
                        <div class="date-display">
                            <?= date("d/m/Y", strtotime($a->data)) ?> às <?= $a->hora ?>
                        </div>

                        <a href="../../../backend/concluirManicure.php?id=<?= $a->id ?>">
                            <button class="confirm-btn">CONCLUIR</button>
                        </a>

                        <a href="../../../backend/rejeitarAgendamento.php?id=<?= $a->id ?>">
                            <button class="cancel-btn">CANCELAR</button>
                        </a>
                    </div>

                    <div class="appointment-info">
                        <p><b>Cliente:</b> <?= $sistema->usuarios[$a->idCliente - 1]->nome ?></p>
                        <p><b>Serviço:</b> <?= $a->servico ?></p>
                    </div>

                    <div class="illustration-container">
                        <img src="../../image/pessoa-agendar.png" class="illustration-img">
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <h2 class="section-subtitle">CONCLUÍDOS</h2>
    <div class="appointments-container">
        <?php if (empty($concluidos)): ?>
            <p class="empty-msg">Nenhum atendimento concluído.</p>
        <?php else: ?>
            <?php foreach ($concluidos as $a): ?>
                <div class="appointment-card done">

                    <div class="date-display done-color">
                        ✔ <?= date("d/m/Y", strtotime($a->data)) ?> às <?= $a->hora ?>
                    </div>

                    <div class="appointment-info">
                        <p><b>Cliente:</b> <?= $sistema->usuarios[$a->idCliente - 1]->nome ?></p>
                        <p><b>Serviço:</b> <?= $a->servico ?></p>
                        <p><b>Status:</b> FINALIZADO</p>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <h2 class="section-subtitle">CANCELADOS</h2>
    <div class="appointments-container">
        <?php if (empty($cancelados)): ?>
            <p class="empty-msg">Nenhum agendamento cancelado.</p>
        <?php else: ?>
            <?php foreach ($cancelados as $a): ?>
                <div class="appointment-card cancelled">

                    <div class="date-display cancelled-color">
                        ✖ <?= date("d/m/Y", strtotime($a->data)) ?> às <?= $a->hora ?>
                    </div>

                    <div class="appointment-info">
                        <p><b>Cliente:</b> <?= $sistema->usuarios[$a->idCliente - 1]->nome ?></p>
                        <p><b>Serviço:</b> <?= $a->servico ?></p>
                        <p><b>Status:</b> CANCELADO</p>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</main>

</body>
</html>
