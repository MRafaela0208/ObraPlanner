<?php
session_start();
include_once '../includes/db_connect.php';

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$fiscal_id = $_SESSION['UsuarioID'];

$sqlFiscal = "SELECT empresa_id FROM fiscais WHERE fiscal_id = ?";
$stmtFiscal = $conn->prepare($sqlFiscal);
$stmtFiscal->bind_param("i", $fiscal_id);
$stmtFiscal->execute();
$resultFiscal = $stmtFiscal->get_result();

if ($resultFiscal->num_rows > 0) {
    $fiscalData = $resultFiscal->fetch_assoc();
    
    $_SESSION['EmpresaID'] = $fiscalData['empresa_id'];
} else {
    echo "Fiscal not found.";
    exit();
}

$empresa_id = $_SESSION['EmpresaID'];

$resultFuncionarios = $conn->query("SELECT nome, telefone FROM funcionarios WHERE empresa_id = $empresa_id");
$funcionariosCount = $resultFuncionarios->num_rows;

$resultProjetos = $conn->query("SELECT projeto_id, titulo, status, data_inicio, data_termino FROM projetos WHERE fiscal_id = $fiscal_id");
$projetosCount = $resultProjetos->num_rows;

$resultNotificacoes = $conn->query("SELECT mensagem, data_criacao FROM notificacoes WHERE usu_id = $fiscal_id");
$notificacoesCount = $resultNotificacoes->num_rows;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>ObraPlanner</title>
    <style>
         .container {
        background-color: #fff;
        margin-bottom: 20px;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .container h3 {
        color: #01306E;
        margin-bottom: 15px;
        font-size: 22px;
        font-weight: bold;
        border-bottom: 2px solid #f0f8ff;
        padding-bottom: 10px;
    }

    .container ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .container ul li {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        font-size: 16px;
    }

    .container ul li:last-child {
        border-bottom: none;
    }

    .container ul li:hover {
        background-color: #f0f8ff;
    }
    li strong{
        color: #135545;
    }
    .welcome-message {
        display: flex;
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 20px; 
        background-color:#fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 30px;
        border-radius: 10px;
    }

    .welcome-text {
        flex: 1; 
        margin-right: 20px; 
        color: #000;
        font-weight: 500;
    }

    .welcome-text h2{
        color:#135545;
        font-weight: 700;
}
    .welcome-image img {
        max-width: 100%; 
        height: auto;
    }

    </style>
</head>
<body>
<body>
    <header>
        <div class="logo">
            <img src="../imgs/obraplanner7.png" alt="Logo do ObraPlanner">
        </div>
    </header>

    <nav class="sidebar">
        <ul>
            <li><a href="dash.php"><i class="bx bx-home"></i> Home</a></li>
            <li><a href="projetos/proj.php"><i class="bx bx-folder"></i> Projetos</a></li>
            <li><a href="notificacaos/not.php"><i class="bx bx-bell"></i> Notificações</a></li>
            <li><a href="config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
            <li><a href="../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
        </ul>
    </nav>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
    <?php if ($projetosCount == 0 && $notificacoesCount == 0 && $funcionariosCount == 0): ?>
    <div class="welcome-message">
        <div class="welcome-text">
            <h2>Bem-vindo ao ObraPlanner!</h2>
            <p>É um prazer tê-lo conosco como nosso fiscal. Atualmente, você ainda não possui projetos, notificações ou funcionários registrados. Para começar a sua jornada, explore o sistema e fique atento para as atualizações dos projetos que você irá fiscalizar.</p>
            <p>Nosso sistema foi projetado para facilitar a supervisão de suas obras, garantindo que tudo esteja em conformidade e sob controle. Utilize nossas ferramentas para acompanhar o progresso dos projetos e receber notificações importantes.</p>
        </div>
        <div class="welcome-image">
            <img src="imgs/fiscal.png" alt="Imagem de boas-vindas">
        </div>
    </div>
<?php else: ?>
    <section class="container">
        <h3>Notificações</h3>
        <ul>
            <?php if ($notificacoesCount > 0): ?>
                <?php while ($notificacao = $resultNotificacoes->fetch_assoc()): ?>
                    <li><?php echo htmlspecialchars($notificacao['mensagem']) . " - " . date('d/m/Y', strtotime($notificacao['data_criacao'])); ?></li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>Sem Nenhuma Notificação</li>
            <?php endif; ?>
        </ul>
    </section>

    <section class="container">
        <h3>Funcionários Registrados</h3>
        <ul>
            <?php if ($funcionariosCount > 0): ?>
                <?php while ($row = $resultFuncionarios->fetch_assoc()): ?>
                    <li><?php echo htmlspecialchars($row['nome']) . " - Telefone: " . htmlspecialchars($row['telefone']); ?></li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>Nenhum funcionário encontrado.</li>
            <?php endif; ?>
        </ul>
    </section>

    <section class="container">
        <h3>Projetos Ativos</h3>
        <ul>
            <?php if ($projetosCount > 0): ?>
                <?php while ($projeto = $resultProjetos->fetch_assoc()): ?>
                    <li><strong><?php echo htmlspecialchars($projeto['titulo']); ?></strong>
                    <?php echo " " . date('d/m/Y', strtotime($projeto['data_inicio'])) . " | " . date('d/m/Y', strtotime($projeto['data_termino'])); ?></li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>Sem Nenhum Projeto Ativo</li>
            <?php endif; ?>
        </ul>
    </section>
<?php endif; ?>
    </main>
</body>
</html>
<?php
$conn->close();
?>
