<?php
require_once "../../../backend/sistema.php";
require_once "../../../backend/protegerAdmin.php";

$sistema = Sistema::getInstancia();
$usuario = $sistema->obterUsuarioLogado();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlamourTime - Admin Cadastrar Horário</title>
    <link rel="stylesheet" href="../../css/components.css">
    <link rel="stylesheet" href="../../css/cadastrarHorario.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<?php include "sidebarAdmin.php"; ?>

<main class="main-content">

    <header class="header-top">
        <h1 class="page-title">NOVO HORÁRIO</h1>
        
        <div class="header-actions">
            <div class="search-bar">
                <input type="text" placeholder="PESQUISAR ........." class="search-input">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <div class="user-avatar-small">
                <img src="https://img.freepik.com/premium-photo/3d-avatar-cartoon-character_113255-93278.jpg" alt="Admin Avatar">
            </div>
        </div>
    </header>

    <div class="register-container">
        <div class="register-card">

            <div class="card-header">
                <h2>ADICIONAR NOVA DISPONIBILIDADE</h2>
                <p>Defina a data e o horário em que você deseja abrir sua agenda.</p>
            </div>

            <form action="../../../backend/cadastrarHorario.php" method="POST">

                <div class="form-group">
                    <label class="form-label">DATA DO ATENDIMENTO</label>
                    <input type="date" class="form-input" name="data" required>
                </div>

                <div class="form-group">
                    <label class="form-label">HORÁRIO DISPONÍVEL</label>
                    <input type="time" class="form-input" name="hora" required>
                </div>

                <button type="submit" class="submit-btn">ADICIONAR DISPONIBILIDADE</button>

            </form>

        </div>
    </div>

</main>

</body>
</html>
