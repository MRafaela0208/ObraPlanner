<?php
session_start();
$error = isset($_SESSION['login_erro']) ? $_SESSION['login_erro'] : '';
unset($_SESSION['login_erro']); 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ObraPlanner</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<style>
    p {
    margin-top: 20px;
    font-size: 14px;
    color: #555555;
    margin-right: 125px;
}

p a {
    color: #01306e;
    text-decoration: none;
    font-weight: bold;
    margin-left: 5px;
}

p a:hover {
    text-decoration: underline;
}
</style>
<body>
    <header>
        <img src="../imgs/obraplanner7.png" alt="Logo" class="logo"  onclick="window.location.href='../index.php'">
    </header>
    <main>
        <div class="left-side">
        </div>
        <div class="right-side">
            <h1>Login</h1>
            <form action="validacao.php" method="post">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="input-group">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" name="senha" id="senha" required>
                </div>
                <?php if (!empty($error)): ?>
                    <div class="mensagem-erro" style="text-align: center; color:red;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <br>
                <button class="btn" type="submit">Entrar</button>
            </form>
            <p>Não tem uma conta?<a href="../cadastro/cademp.php">Cadastre-se Agora!</a></p>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Obra Planner. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
