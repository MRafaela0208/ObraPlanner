<?php
include '../../includes/db_connect.php';

if (isset($_GET['id'])) {
    $fiscal_id = $_GET['id'];

    $sql = "SELECT * FROM fiscais WHERE fiscal_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fiscal_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $fiscal = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $cpf = $_POST['cpf'];
        $senha = $_POST['senha']; 

        if (empty($senha)) {
            $update_sql = "UPDATE fiscais SET nome = ?, email = ?, telefone = ?, cpf = ? WHERE fiscal_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssssi", $nome, $email, $telefone, $cpf, $fiscal_id);
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $update_sql = "UPDATE fiscais SET nome = ?, email = ?, telefone = ?, cpf = ?, senha = ? WHERE fiscal_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssssi", $nome, $email, $telefone, $cpf, $senha_hash, $fiscal_id);
        }

        if ($update_stmt->execute()) {
            header("Location: fis.php?message=Fiscal atualizado com sucesso!");
            exit;
        } else {
            echo "Erro ao atualizar fiscal: " . $update_stmt->error;
        }
    }
} else {
    echo "ID do fiscal não fornecido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>ObraPlanner</title>
</head>
<body>
    <style>
        h2 {
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
        <h2>Editar Fiscal</h2>
        <form method="POST">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($fiscal['nome']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($fiscal['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($fiscal['telefone']); ?>">
            </div>
            <div class="form-group">
                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($fiscal['cpf']); ?>" required>
            </div>
            <div class="form-group">
                <label for="senha">Nova Senha (deixe em branco para não alterar):</label>
                <input type="password" id="senha" name="senha">
            </div>
            <div class="button-container mt-3">
                <button type="submit" class="btncriar">Atualizar</button>
                <a href="fis.php" class="btnback">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>
