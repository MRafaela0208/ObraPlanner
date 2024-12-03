<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../includes/db_connect.php';
session_start(); 

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$func_id = $_SESSION['UsuarioID']; 

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT p.projeto_id, p.titulo AS projeto_titulo, p.descricao, p.imagem, p.documentacao, p.data_inicio, p.data_termino, 
                 p.data_prev_ini, p.data_prev_ter, p.status, p.qtn_eta, p.progresso,
                 e.titulo AS etapa_titulo, e.etapa_id
          FROM projetos p
          LEFT JOIN etapas e ON p.projeto_id = e.projeto_id
          JOIN projeto_funcionarios pf ON p.projeto_id = pf.projeto_id
          WHERE pf.func_id = ?"; 

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $func_id);
$stmt->execute();
$result = $stmt->get_result();

$projetos = [];
while ($data = $result->fetch_assoc()) {
    $projetos[$data['projeto_id']]['titulo'] = $data['projeto_titulo'];
    $projetos[$data['projeto_id']]['descricao'] = $data['descricao'];
    $projetos[$data['projeto_id']]['imagem'] = $data['imagem'];
    $projetos[$data['projeto_id']]['documentacao'] = $data['documentacao'];
    $projetos[$data['projeto_id']]['data_inicio'] = $data['data_inicio'];
    $projetos[$data['projeto_id']]['data_termino'] = $data['data_termino'];
    $projetos[$data['projeto_id']]['data_prev_ini'] = $data['data_prev_ini'];
    $projetos[$data['projeto_id']]['data_prev_ter'] = $data['data_prev_ter'];
    $projetos[$data['projeto_id']]['status'] = $data['status'];
    $projetos[$data['projeto_id']]['qtn_eta'] = $data['qtn_eta'];
    $projetos[$data['projeto_id']]['progresso'] = $data['progresso'];
    $projetos[$data['projeto_id']]['etapas'][] = [
        'titulo' => $data['etapa_titulo'],
        'etapa_id' => $data['etapa_id'],
    ];
}

$tem_projetos = !empty($projetos);
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
</head>
<style>
    h1 {
        color: #135545;
        text-align: center;
    }
    .project-list {
        display: flex;
        flex-direction: column;
        gap: 20px; 
    }

    .project-card {
        margin-top: 10px;
        background-color: #fff; 
        border: 1px solid #dee2e6;
        border-radius: 10px; 
        padding: 20px; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
    }

    .project-card h2 {
        margin: 0 0 10px;
        color: #274a70;
    }

    .project-card p strong {
        color: #135545;
    }

    .details-btn { 
        cursor: pointer; 
        display: block; 
        margin-top: 10px; 
        background-color: white;
    }
    .details-btn:hover {
        background-color: white;
    }
    .details-btn a {
        background-color: #074470;
        color: white;
        border: solid 1px #074470;
        padding: 10px 20px; 
        border-radius: 10px;
    }

    .details-btn a:hover {
        background-color: #fff;
        color: #074470;
        border: solid 1px #074470;
    }

    .no-projects {
        display: flex;
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 20px; 
        background-color:#fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 30px;
        border-radius: 10px;
    }
    .no-projects h2 {
        color:#135545;
        font-weight: 700;
    }
    .no-projects p {
        flex: 1; 
        margin-right: 20px; 
        color: #000;
        font-weight: 500;
    }
    .no-projects img {
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
        <li><a href="../notificacaos/not.php"><i class="bx bx-bell"></i> Notificações</a></li>
        <li><a href="../presenca/presenca.php"><i class="bx bx-task"></i> Presença</a></li>
        <li><a href="../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
        <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
    </ul>
</nav>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">  
<div class="search-form">
        <form class="form-inline" method="GET" action="">
            <div class="input-group">
                <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Buscar por projeto...">
                <button class="btn btn-primary" type="submit"><i class="bx bx-search"></i></button>
            </div>
        </form>
    </div>
    <section class="project-list">
        <?php if (!$tem_projetos): ?>
            <div class="no-projects">
                <div>
                    <h2>Bem-vindo à Central de Projetos</h2>
                    <p>Você ainda não possui projetos registrados. Como funcionário, você pode visualizar os detalhes dos projetos, mas não tem permissão para criar ou gerenciar projetos. Inicie acompanhando os projetos disponíveis!</p>
                </div>
                <img src="../imgs/mococons.png" alt="Explicação sobre a página de projetos">
            </div>
        <?php else: ?>
            <div class="no-projects">
                <div>
                    <h2>Central de Projetos</h2>
                    <p>Esta é a página onde você pode visualizar os detalhes dos projetos que estão sob sua responsabilidade. Selecione um projeto para ver mais informações.</p>
                </div>
                <img src="../imgs/mococons.png" alt="Explicação sobre a página de projetos">
            </div>
            <?php foreach ($projetos as $projeto_id => $projeto): ?>
                <div class="project-card">
                <h2><?php echo htmlspecialchars($projeto['titulo']); ?></h2>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($projeto['status']); ?></p>
                <p><strong>Data Início:</strong> 
                <?php echo htmlspecialchars((new DateTime($projeto['data_inicio']))->format('d/m/Y')); ?></p>
                <p><strong>Prazo:</strong> 
                <?php echo htmlspecialchars((new DateTime($projeto['data_termino']))->format('d/m/Y')); ?></p>
                <p><strong>Data Prevista Início:</strong> 
                <?php echo htmlspecialchars((new DateTime($projeto['data_prev_ini']))->format('d/m/Y')); ?></p>
                <p><strong>Data Prevista Término:</strong> 
                <?php echo htmlspecialchars((new DateTime($projeto['data_prev_ter']))->format('d/m/Y')); ?></p>
                <p><strong>Progresso:</strong> <?php echo htmlspecialchars($projeto['progresso'] ?? ''); ?>%</p>
                    <p><strong>Etapas:</strong>
                        <?php 
                            $etapa_titles = array_map(function($etapa) {
                                return htmlspecialchars($etapa['titulo']);
                            }, $projeto['etapas']);
                            echo implode(' | ', $etapa_titles);
                        ?>
                    </p>
                    <button class="details-btn">
                        <a href="proj2.php?projeto_id=<?php echo $projeto_id; ?>" style="text-decoration: none;">Ver Detalhes</a>
                    </button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
