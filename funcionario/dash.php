<?php 
include '../includes/db_connect.php';

session_start();

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$func_id = $_SESSION['UsuarioID'];

$con = new mysqli('localhost', 'root', '', 'obra_planner');
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$sql = "SELECT nome, email FROM funcionarios WHERE func_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $func_id);
$stmt->execute();
$query = $stmt->get_result();

if ($query->num_rows > 0) {
    $result = $query->fetch_assoc();
} else {
    echo "Erro na consulta: " . $con->error;
    exit();
}

$sqlProjetos = "SELECT p.projeto_id, p.titulo, p.descricao, p.status, p.data_inicio, p.data_termino, p.progresso, p.qtn_eta
                FROM projetos p
                JOIN projeto_funcionarios pf ON p.projeto_id = pf.projeto_id
                WHERE pf.func_id = ?";
$stmtProjetos = $con->prepare($sqlProjetos);
$stmtProjetos->bind_param("s", $func_id); 
$stmtProjetos->execute();
$resultProjetos = $stmtProjetos->get_result();

$sqlNotificacoes = "SELECT mensagem, data_criacao FROM notificacoes WHERE usu_id = ?";
$stmtNotificacoes = $con->prepare($sqlNotificacoes);
$stmtNotificacoes->bind_param("i", $func_id);
$stmtNotificacoes->execute();
$resultNotificacoes = $stmtNotificacoes->get_result();

$showWelcomeMessage = ($resultProjetos->num_rows == 0 && $resultNotificacoes->num_rows == 0);

if (isset($_POST['checkin'])) {
    $data = date('Y-m-d'); 
    $checkin = date('Y-m-d H:i:s'); 
    $stmt = $con->prepare("INSERT INTO presenca (func_id, data, checkin) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $func_id, $data, $checkin);
    $stmt->execute();
}

if (isset($_POST['checkout'])) {
    $checkout = date('Y-m-d H:i:s'); 
    $stmt = $con->prepare("UPDATE presenca SET checkout = ? WHERE func_id = ? AND data = ? AND checkout IS NULL");
    $stmt->bind_param("sss", $checkout, $func_id, $data);
    $stmt->execute();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>ObraPlanner</title>
    <style>
        .container ul ul { 
            padding-left: 20px;
        }

        .container ul ul li {
            font-size: 14px;
            color: #555;
        }

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
            border-bottom: 2px solid #f0f8ff;
            padding-bottom: 10px;
            font-size: 20px;
        }
        
        li strong {
            color: #135545;
        }
        
        .container ul {
            list-style: none;
            padding-left: 0;
        }

        .container ul li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
        }

        .welcome-section {
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
        .welcome-text h2 {
            color:#135545;
            font-weight: 700;
        }
        .welcome-image { 
            max-width: 100%; 
            height: auto;
        }

        .checkin-section {
            margin: 20px 0;
        }

        .checkin-section button {
            margin-right: 10px;
        }
    </style>
</head>
<body>
<header>
    <div class="logo">
        <img src="../imgs/obraplanner7.png" alt="Logo do ObraPlanner" style="max-height: 50px;">
    </div>
</header>
<nav class="sidebar">
    <ul>
        <li><a href="dash.php"><i class="bx bx-home"></i> Home</a></li>
        <li><a href="projetos/proj.php"><i class="bx bx-folder"></i> Projetos</a></li>
        <li><a href="notificacaos/not.php"><i class="bx bx-bell"></i> Notificações</a></li>
        <li><a href="presenca/presenca.php"><i class="bx bx-task"></i> Presença</a></li>
        <li><a href="config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
        <li><a href="../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
    </ul>
</nav>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">

<?php if ($showWelcomeMessage): ?>
    <div class="welcome-section">
        <div class="welcome-text">
            <h2>Bem-vindo ao ObraPlanner!</h2>
            <p>Estamos muito felizes em tê-lo como parte da nossa equipe! Neste espaço, você poderá gerenciar suas atividades de forma prática e eficiente.</p>
            <p>Atualmente, você ainda não tem projetos ou notificações, mas fique à vontade para explorar o sistema. Em breve, você poderá acompanhar o progresso das suas obras, interagir com sua equipe e manter tudo organizado.</p>
            <p>Nosso objetivo é facilitar seu trabalho e garantir que você tenha as informações necessárias para contribuir da melhor maneira. Vamos juntos transformar sua experiência em obra!</p>
        </div>
        <div class="welcome-image">
            <img src="imgs/funcs2.png" alt="Imagem de boas-vindas">
        </div>
    </div>
<?php else: ?>
    <section class="container">
        <h3>Registro de Presença</h3>
        <form method="POST">
            <button type="submit" name="checkin" class="btn btn-success">Check-in</button>
            <button type="submit" name="checkout" class="btn btn-danger">Check-out</button>
        </form>
    </section>

    <section class="container">
        <h3>Notificações</h3>
        <ul>
            <?php if ($resultNotificacoes->num_rows > 0): ?>
                <?php while ($notificacao = $resultNotificacoes->fetch_assoc()): ?>
                    <li><?php echo htmlspecialchars($notificacao['mensagem']) . " - " . date('d/m/Y', strtotime($notificacao['data_criacao'])); ?></li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>Sem Nenhuma Notificação</li>
            <?php endif; ?>
        </ul>
    </section>

    <section class="container">
        <h3>Projetos Atuais</h3>
        <ul>
            <?php if ($resultProjetos->num_rows > 0): ?>
                <?php while ($projeto = $resultProjetos->fetch_assoc()): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($projeto['titulo']); ?></strong><br>
                        <em><?php echo htmlspecialchars($projeto['descricao']); ?></em><br>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>Sem Projetos Atuais</li>
            <?php endif; ?>
        </ul>
    </section>
<?php endif; ?>

</main>
</body>
</html>
