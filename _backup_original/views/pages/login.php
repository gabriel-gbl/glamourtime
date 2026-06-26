<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlamourTime - Login</title>
    <link rel="stylesheet" href="../css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>

    <main class="container">

        <section class="hero-section"></section>

        <section class="form-section">
            <section class="form-wrapper">

                <header class="logo-container">
                    <img src="../image/logo-pink.png" alt="" class="logo">
                </header>

                <h2 class="page-title">Login</h2>

                <form action="../../backend/login.php" method="POST" style="width: 100%;">  

                    <section class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <section class="input-wrapper">
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                class="form-input" 
                                placeholder="Insira seu Email:" 
                                required
                            >
                        </section>
                    </section>

                    <section class="form-group">
                        <label for="senha" class="form-label">Senha</label>
                        <section class="input-wrapper">
                            <input 
                                type="password" 
                                name="senha" 
                                id="senha" 
                                class="form-input" 
                                placeholder="Insira sua Senha:" 
                                required
                            >
                        </section>
                    </section>

                    <button type="submit" class="btn btn-primary">
                        Entrar
                    </button>

                    <a href="../../index.php">
                        <button type="button" class="btn btn-outline">
                            Fazer Cadastro
                        </button>
                    </a>

                </form>

            </section>
        </section>

    </main>

</body>
</html>
