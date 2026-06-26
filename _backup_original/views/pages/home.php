<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlamourTime - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/components.css">
</head>

<body>
    <?php include "./sidebar.php"; ?>

    <main class="main-content">
        <?php include "./header.php"; ?>

        <section class="promo-banner">
            <div class="pin-decoration">📌</div>

            <div class="banner-image-container">
                <img src="../image/image-promo.png"
                     alt="Agendamento 3D" class="banner-img-mockup">
            </div>

            <div class="banner-content">
                <hgroup>
                    <h3 class="banner-title">SEU TEMPO DE BELEZA EM UM CLIQUE</h3>
                    <p class="banner-subtitle">
                        Agende agora sua manicure de forma prática e rápida.
                        Não perca tempo, garanta já o seu horário.
                    </p>
                </hgroup>
                <a href="./agendarHorario.php">
                    <button class="btn-banner">AGENDAR HORÁRIO</button>
                </a>
            </div>
        </section>

        <section class="dashboard-grid">

            <div class="info-card">
                <h3>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    Fidelidade Glamour
                </h3>

                <div class="loyalty-container">
                    <div class="loyalty-stars">
                        <span class="star-filled">★</span>
                        <span class="star-filled">★</span>
                        <span class="star-filled">★</span>
                        <span class="star-filled">★</span>
                        <span class="star-filled">★</span>
                        <span class="star-filled">★</span>
                        <span class="star-filled">★</span>
                        <span class="star-empty">★</span>
                        <span class="star-empty">★</span>
                        <span class="star-empty">★</span>
                    </div>

                    <p class="loyalty-info">Você tem <strong>700 pontos</strong></p>

                    <div class="progress-bar-container">
                        <div class="progress-bar-fill"></div>
                    </div>

                    <p class="loyalty-footer">Faltam 3 visitas para ganhar um Spa dos Pés!</p>
                </div>
            </div>

        </section>

    </main>

</body>

</html>
