<?php
require_once "../../backend/sistema.php";
$sistema = Sistema::getInstancia();
$usuario = $sistema->obterUsuarioLogado();

if (!$usuario) {
    header("Location: ../login.php");
    exit;
}

$remarcarId = $_GET['remarcar'] ?? null;
$remarcarAgendamento = null;

if ($remarcarId) {
    $lista = $sistema->listarAgendamentosCliente($usuario->id);

    foreach ($lista as $a) {
        if ($a->id == $remarcarId && $a->status === "pendente") {
            $remarcarAgendamento = $a;
            break;
        }
    }
}

$horariosDisponiveis = $sistema->listarHorariosDisponiveis();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlamourTime - Agendar Horário</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/agendarHorario.css">
    <link rel="stylesheet" href="../css/components.css">
</head>
<body>

<?php include "sidebar.php"; ?>

<main class="main-content">

<?php include "header.php"; ?>

<section class="schedule-panel">

    <section class="illustration-area">
        <img src="../image/pessoa-agendar.png" alt="Pessoa Agendando" class="illustration-img">
    </section>

    <section class="form-area">

        <form action="<?= $remarcarAgendamento ? '../../backend/remarcar.php' : '../../backend/agendarCliente.php' ?>" method="POST">

    <?php if ($remarcarAgendamento): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($remarcarAgendamento->id) ?>">
    <?php endif; ?>

    <section class="form-group">
        <label class="form-label">AGENDAR:</label>
        <input 
            type="text"
            name="servico"
            class="form-control"
            required
            placeholder="Ex: Manicure e Pedicure"
            value="<?= $remarcarAgendamento ? htmlspecialchars($remarcarAgendamento->servico) : '' ?>"
        >
    </section>

    <section class="form-group">
        <label class="form-label">SELECIONE UM HORÁRIO:</label>

        <select name="horarioEscolhido" class="form-control" required>
            <option value="">Selecione...</option>

            <?php foreach ($horariosDisponiveis as $h): 
                $value = $h['data'] . '|' . $h['hora'];
                $texto = date("d/m/Y", strtotime($h['data'])) . " às " . substr($h['hora'], 0, 5);

                $selected = (
                    $remarcarAgendamento &&
                    $remarcarAgendamento->data == $h["data"] &&
                    $remarcarAgendamento->hora == $h["hora"]
                ) ? "selected" : "";
            ?>
                <option value="<?= $value ?>" <?= $selected ?>>
                    <?= $texto ?>
                </option>
            <?php endforeach; ?>
        </select>
    </section>

    <button type="submit" class="btn-submit">
        <?= $remarcarAgendamento ? 'REMARCAR' : 'AGENDAR' ?>
    </button>

</form>

    </section>

</section>

</main>

</body>
</html>
