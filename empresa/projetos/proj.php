<?php
session_start();

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$empresa_id = $_SESSION['UsuarioID'];

$conn = mysqli_connect('localhost', 'root', '', 'obra_planner');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$searchTerm = isset($_GET['search']) && $_GET['search'] !== '' ? '%' . $_GET['search'] . '%' : '%';

$stmt = $conn->prepare("SELECT * FROM projetos WHERE empresa_id = ? AND titulo LIKE ?");
$stmt->bind_param("is", $empresa_id, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$stmtFiscais = $conn->prepare("SELECT fiscal_id, nome FROM fiscais");
$stmtFiscais->execute();
$resultFiscais = $stmtFiscais->get_result();
$fiscais = [];

while ($fiscal = $resultFiscais->fetch_assoc()) {
    $fiscais[$fiscal['fiscal_id']] = htmlspecialchars($fiscal['nome']);
}

$stmt->close();
$stmtFiscais->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>ObraPlanner</title>
    <style>
        h1 {
            color: #135545;
            text-align: center;
        }
        h2 { margin-top: 20px; color: var(--co4); }
        .project-container {
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px 0;
            display: flex;
            flex-direction: column;
        }
        .project-info {
            display: flex;
            justify-content: space-between;
            width: 100%;
            flex-wrap: nowrap;
            margin-bottom: 10px;
        }
        .project-title {
            font-weight: bold;
            margin-right: 15px; 
            color: #135545;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: flex-end; 
            margin-top: 10px;
        }
        .btnproj {
            padding: 0.1rem 0.5rem;
            font-size: 1rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block; 
        }
        .btn-criar-projeto, .btncriar {
            background-color: #fff; 
            color: #074470; 
            padding: 10px 15px; 
            border: solid 1px #074470; 
            border-radius: 5px; 
            cursor: pointer; 
        }
        .btncriar:hover {
            background-color: #074470;
            color: #fff;
        }
        div strong {
            color: #324363;
        }
        .info-container {
            display: flex;
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 20px; 
            background-color:#fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 10px;
        }
        .info-text {
            flex: 1; 
            padding: 20px;
            margin-right: 15px; 
            color: #555;
            font-weight: 500;
        }
        .info-text h2{
            color:#135545;
            font-weight: 700;
        }
        .info-image img {
            max-width: 100%; 
            height: auto; 
        }
        .form-inline .input-group button {
    background-color: #074470;
    border: none;
    padding: 8px;
    color: white;
    font-size: 18px;
    border-radius: 5px;
}

.form-inline .input-group input {
    border-radius: 5px;
    padding: 8px;
    border: 1px solid #ccc;
}

.search-form {
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
    align-items: center;  
    width: 100%; 
    max-width: 600px; 
    margin-left: auto; 
    margin-right: auto; 
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
            <li><a href="proj.php"><i class="bx bx-folder"></i> Projetos</a></li>
            <li><a href="../fiscal/fis.php"><i class="bx bx-user"></i> Fiscais</a></li>
            <li><a href="../funcionarios/func.php"><i class="bx bx-user"></i> Funcionários</a></li>
            <li><a href="../notificacaos/not.php"><i class="bx bx-bell"></i> Notificações</a></li>
            <li><a href="../relemp/relemp.php"><i class="bx bx-task"></i> Relatórios</a></li>
            <li><a href="../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
            <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
        </ul>
    </nav>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
    <form action="proj.php" method="GET" class="form-inline search-form">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar projetos..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="max-width: 400px;">
            <button type="submit" class="btn">
                <i class="bx bx-search"></i>
            </button>
        </div>
    </form>
    <div class="info-container">
    <div class="info-text">
        <h2>Bem-vindo à Central de Projetos!</h2>
        <p>Visualize todos os projetos da sua empresa e acompanhe o progresso de cada etapa com facilidade. Utilize o botão abaixo para iniciar um novo projeto e gerenciá-lo de forma eficiente.</p>
        <button class="btncriar" onclick="window.location.href='addproj.php'"><i class="bx bx-plus-circle"></i> Criar Novo Projeto</button>
        <button class="btncriar" onclick="window.location.href='relatorio.php'"><i class="bx bx-download"></i> Gerar Relatório</button>
    </div>
    <div class="info-image">
        <img src="../imgs/projetos4.png" alt="Imagem ilustrativa de gestão de projetos">
    </div>
</div>
<section class="projects-overview">
    <div class="lista">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($projeto = $result->fetch_assoc()): ?>
                <div class="project-container">
                    <div class="project-info">
                        <div class="project-title"><?php echo htmlspecialchars($projeto['titulo']); ?></div>
                        <div><strong>Status:</strong> <?php echo htmlspecialchars($projeto['status']); ?></div>
                        <div><strong>Responsável:</strong> 
                            <?php 
                            echo isset($fiscais[$projeto['fiscal_id']]) ? htmlspecialchars($fiscais[$projeto['fiscal_id']]) : 'N/A'; 
                            ?>
                        </div>
                        <div><strong>Data:</strong> <?php echo date("d/m/Y", strtotime($projeto['data_inicio'])); ?> | <?php echo date("d/m/Y", strtotime($projeto['data_termino'])); ?></div>
                    </div>
                    <div class="btn-group">
                        <a href="verproj.php?id=<?php echo $projeto['projeto_id']; ?>" class="btnproj" style="color: #4CAF50;"> 
                        <i class='bx bx-show'></i>
                        </a>
                        <a href="editproj.php?id=<?php echo $projeto['projeto_id']; ?>" class="btnproj" style="color: #2196F3;"> 
                        <i class='bx bx-pencil'></i> Editar
                        </a>
                        <a href="delproj.php?id=<?php echo $projeto['projeto_id']; ?>" class="btnproj" style="color: #F44336;"> 
                        <i class='bx bx-trash'></i> Excluir
                        </a>
                        </div>
                    </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center;">Não há projetos cadastrados no momento. Clique em "Criar Novo Projeto" para iniciar seu primeiro projeto.</p>
        <?php endif; ?>
    </div>
</section>
    </main>
</body> 
</html>
