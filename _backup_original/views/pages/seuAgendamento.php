<?php
require_once "../../backend/sistema.php";
$s = Sistema::getInstancia();
$usuario = $s->obterUsuarioLogado();

if (!$usuario) {
    header("Location: ../login.php");
    exit;
}

// PEGAR APENAS O AGENDAMENTO ATIVO
$lista = $s->listarAgendamentosCliente($usuario->id);

$agendamento = null;
foreach ($lista as $a) {
    if ($a->status === "pendente") {
        $agendamento = $a;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>GlamourTime - Seu Agendamento</title>
    <link rel="stylesheet" href="../css/seuAgendamento.css">
    <link rel="stylesheet" href="../css/components.css">
</head>
<body>

<?php include "sidebar.php"; ?>

<main class="main-content">

<?php include "header.php"; ?>

<section class="appointment-panel">
    <section class="content-container">

        <section class="info-section">
            <h2 class="section-title">SEU ATENDIMENTO</h2>

            <?php if (!$agendamento): ?>

                <p class="no-appointment">Você não possui agendamentos no momento.</p>

            <?php else: ?>

                <section class="date-display">
                    <?= date("d/m/Y", strtotime($agendamento->data)) ?>
                    às <?= $agendamento->hora ?>
                </section>

                <a href="agendarHorario.php?remarcar=<?= $agendamento->id ?>">
                    <button class="btn-action btn-reschedule">REMARCAR DATA/HORA</button>
                </a>

                <a href="../../backend/cancelar.php?id=<?= $agendamento->id ?>"
                   onclick="return confirm('Deseja realmente cancelar seu atendimento?');">
                    <button class="btn-action btn-cancel">DESMARCAR DATA/HORA</button>
                </a>

            <?php endif; ?>
        </section>

        <section class="illustration-section">
            <img src="../image/pessoa-atendimento.png" class="illustration-img">
        </section>

    </section>
</section>

</main>
</body>
</html>
