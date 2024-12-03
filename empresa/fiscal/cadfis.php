<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>ObraPlanner</title>
    <style>
        h1 {
            text-align: center;
           color: #135545;
            margin-bottom: 20px;
        }

        form {
            width: 100%;
            max-width: 700px; 
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 0 auto; 
        }

        label {
            font-weight: bold;
            color: #074470;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"] {
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 15px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .button-container button,
        .button-container a {
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
        }

        .btncriar{
            background-color: #fff; 
            color:#145413;
            border: solid 1px #145413; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold;
        }
        .btncriar:hover{
            background-color:#145413;
            color:#fff;
            border: solid 1px #145413; 
        }
        .btnback{
            background-color: #fff; 
            color:#541313;
            border: solid 1px #541313; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold;
        }
        .btnback:hover{
            background-color:#541313;
            color:#fff;
            border: solid 1px #541313; 
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="../../imgs/obraplanner7.png" alt="Logo do ObraPlanner" style="max-height: 50px;">
        </div>
    </header>
    <nav class="sidebar">
        <ul>
            <li><a href="../dash.php"><i class="bx bx-home"></i> Home</a></li>
            <li><a href="../projetos/proj.php"><i class="bx bx-folder"></i> Projetos</a></li>
            <li><a href="fis.php"><i class="bx bx-user"></i> Fiscais</a></li>
            <li><a href="../funcionarios/func.php"><i class="bx bx-user"></i> Funcionários</a></li>
            <li><a href="../notificacaos/not.php"><i class="bx bx-bell"></i> Notificações</a></li>
            <li><a href="../relemp/relemp.php"><i class="bx bx-task"></i> Relatórios</a></li>
            <li><a href="../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
            <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
        </ul>
    </nav>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">       
        <h1>Cadastrar Fiscal</h1>
        <form action="processo_fis.php" method="POST">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" class="form-control" required oninput="formatarTelefone(this)">
            </div>

            <div class="form-group">
                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" class="form-control" required oninput="formatarCPF(this)">
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" class="form-control" required>
            </div>

            <div class="button-container mt-3">
                <button type="submit" class="btncriar">Cadastrar</button>
                <a href="fis.php" class="btnback">Cancelar</a>
            </div>
        </form>
    </main>

    <script>
        function formatarCPF(cpf) {
            cpf.value = cpf.value
                .replace(/\D/g, "")
                .replace(/(\d{3})(\d)/, "$1.$2")
                .replace(/(\d{3})(\d)/, "$1.$2")
                .replace(/(\d{3})(\d{1,2})$/, "$1-$2")
                .slice(0, 14); 
        }

        function formatarTelefone(telefone) {
            telefone.value = telefone.value
                .replace(/\D/g, "")
                .replace(/(\d{2})(\d)/, "($1) $2")
                .replace(/(\d{5})(\d)/, "$1-$2")
                .replace(/(-\d{4})\d+?$/, "$1")
                .slice(0, 15);
        }
    </script>
</body>
</html>
