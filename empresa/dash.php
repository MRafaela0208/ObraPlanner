<?php
include '../includes/db_connect.php';

session_start(); 
if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$empresa_id = $_SESSION['UsuarioID'];

$con = mysqli_connect('localhost', 'root', '', 'obra_planner');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT nome, email FROM empresas WHERE empresa_id = '$empresa_id'";
$query = mysqli_query($con, $sql);

if (!$query) {
    echo "Erro na consulta: " . mysqli_error($con);
    exit();
}

$result = mysqli_fetch_assoc($query);

$resultFuncionarios = $conn->query("SELECT nome, telefone FROM funcionarios WHERE empresa_id = $empresa_id");
$funcionariosCount = $resultFuncionarios->num_rows;

$resultProjetos = $conn->query("SELECT projeto_id, titulo, status, data_inicio, data_termino FROM projetos WHERE empresa_id = $empresa_id");
$projetosCount = $resultProjetos->num_rows; 

$resultFiscais = $conn->query("SELECT nome, telefone FROM fiscais WHERE empresa_id = $empresa_id");
$fiscaisCount = $resultFiscais->num_rows; 

$resultNotificacoes = $conn->query("SELECT mensagem, data_criacao FROM notificacoes WHERE usu_id = $empresa_id");
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
</head>
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
        li strong{
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
            <li><a href="fiscal/fis.php"><i class="bx bx-user"></i> Fiscais</a></li>
            <li><a href="funcionarios/func.php"><i class="bx bx-user"></i> Funcionários</a></li>
            <li><a href="notificacaos/not.php"><i class="bx bx-bell"></i> Notificações</a></li>
            <li><a href="relemp/relemp.php"><i class="bx bx-task"></i> Relatórios</a></li>
            <li><a href="config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
            <li><a href="../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
        </ul>
    </nav>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
    <?php if ($projetosCount == 0 && $notificacoesCount == 0 && $funcionariosCount == 0 && $fiscaisCount == 0): ?>
        <div class="welcome-message">
            <div class="welcome-text">
                <h2>Bem-vindo ao ObraPlanner!</h2>
                <p>Estamos felizes em tê-lo conosco. Como você ainda não tem projetos, notificações, funcionários ou fiscais, sinta-se à vontade para explorar e começar a criar seu primeiro projeto.</p>
                <p>Nosso sistema foi desenvolvido para facilitar a gestão de suas obras, permitindo que você mantenha tudo sob controle de forma simples e eficiente.</p>
            </div>
            <div class="welcome-image">
                <img src="imgs/welcome1.png" alt="Imagem de boas-vindas">
            </div>
        </div>
        <?php else: ?>
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
            <h3>Funcionários Registrados</h3>
            <ul>
                <?php if ($resultFuncionarios->num_rows > 0): ?>
                    <?php while ($funcionario = $resultFuncionarios->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($funcionario['nome']) . " - " . htmlspecialchars($funcionario['telefone']); ?></li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>Sem Nenhum Funcionário Registrado</li>
                <?php endif; ?>
            </ul>
        </section>

        <section class="container">
            <h3>Fiscais Registrados</h3>
            <ul>
                <?php if ($resultFiscais->num_rows > 0): ?>
                    <?php while ($fiscal = $resultFiscais->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($fiscal['nome']) . " - " . htmlspecialchars($fiscal['telefone']); ?></li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>Sem Nenhum Fiscal Registrado</li>
                <?php endif; ?>
            </ul>
        </section>

        <section class="container">
            <h3>Projetos Ativos</h3>
            <ul>
                <?php if ($resultProjetos->num_rows > 0): ?>
                    <?php while ($projeto = $resultProjetos->fetch_assoc()): ?>
                        <li><strong><?php echo htmlspecialchars($projeto['titulo']); ?></strong>                        
                        <ul>
                            <?php
                            $projeto_id = $projeto['projeto_id'];
                            $resultEtapas = $conn->query("SELECT titulo, data_inicio, data_termino FROM etapas WHERE projeto_id = $projeto_id");

                            if ($resultEtapas->num_rows > 0):
                                while ($etapa = $resultEtapas->fetch_assoc()):
                            ?>
                                    <li><?php echo htmlspecialchars($etapa['titulo']) . " " . date('d/m/Y', strtotime($etapa['data_inicio'])) . " |  " . date('d/m/Y', strtotime($etapa['data_termino'])) . ""; ?></li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li>Sem Nenhuma Etapa Registrada</li>
                            <?php endif; ?>
                        </ul>
                        </li>
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
