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

if (!isset($_GET['projeto_id'])) {
    echo "Projeto não encontrado.";
    exit();
}

$projeto_id = $_GET['projeto_id'];

$query = "SELECT titulo AS projeto_titulo, descricao AS projeto_descricao
          FROM projetos
          WHERE projeto_id = ? AND projeto_id IN (SELECT projeto_id FROM projeto_funcionarios WHERE func_id = ?)"; 

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $projeto_id, $func_id);
$stmt->execute();
$result = $stmt->get_result();

$projeto = $result->fetch_assoc();

if (!$projeto) {
    echo "Nenhum detalhe encontrado para este projeto.";
    exit();
}

$query_etapas = "SELECT titulo AS etapa_titulo, descricao AS etapa_descricao, 
                        observacoes, data_previa_inicio, data_previa_termino, 
                        data_inicio, data_termino, imagem
                 FROM etapas
                 WHERE projeto_id = ?"; 

$stmt_etapas = $conn->prepare($query_etapas);
$stmt_etapas->bind_param("i", $projeto_id);
$stmt_etapas->execute();
$result_etapas = $stmt_etapas->get_result();

$stmt->close();
$stmt_etapas->close();
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
        .p {
            text-align: center;
            font-size: 16px;
            font-weight: 500;
            color: #555;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        .btn-custom {
            background-color: white;
            color: #074470;
            border: solid 1px #074470;
            padding: 10px 20px;
            font-size: 1em;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none; 
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #074470;
            color: white;
        }
        .progresso-status {
            display: flex;
            justify-content: center;
            gap: 20px;
            font-size: 1.2em;
            text-align: center;
            margin-bottom: 20px;
        }
        .progresso-status p strong {
            color: #274a70;
        }
        .etapas-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        .etapa-item {
            width: calc(33.33% - 10px);
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        h3 {
            color: #135545;
        }
        .etapa-item label {
            font-weight: bold;
            margin-left: 5px;
            color: #003d7a;
        }
        .etapa-item p {
            margin: 5px 0;
        }
        .etapa-item p strong {
            margin: 5px 0;
            color: #274a70;
            font-weight: 500;
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
        <li><a href="../notificacaos/not.php"><i class="bx bx-bell"></i> Notificações</a></li>
        <li><a href="../presenca/presenca.php"><i class="bx bx-task"></i> Presença</a></li>
        <li><a href="../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
        <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
    </ul>
</nav>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
    <h1><?php echo htmlspecialchars($projeto['projeto_titulo'] ?? ''); ?></h1>
    <p class="p"><?php echo htmlspecialchars($projeto['projeto_descricao'] ?? ''); ?></p>

    <h2>Etapas</h2>
    <div class="etapas-container">
        <?php while ($etapa = $result_etapas->fetch_assoc()): ?>
            <div class="etapa-item">
                <h3><?php echo htmlspecialchars($etapa['etapa_titulo'] ?? ''); ?></h3>
                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($etapa['etapa_descricao'] ?? ''); ?></p>
                <p><strong>Observações:</strong> <?php echo htmlspecialchars($etapa['observacoes'] ?? ''); ?></p>
                <p><strong>Data Início:</strong> 
                <?php echo htmlspecialchars((new DateTime($etapa['data_inicio']))->format('d/m/Y')); ?></p>
                <p><strong>Data de Término:</strong>
                <?php echo htmlspecialchars((new DateTime($etapa['data_termino']))->format('d/m/Y')); ?></p>
                <p><strong>Data Prevista Início:</strong> 
                <?php echo htmlspecialchars((new DateTime($etapa['data_previa_inicio']))->format('d/m/Y')); ?></p>
                <p><strong>Data Prevista Término:</strong> 
                <?php echo htmlspecialchars((new DateTime($etapa['data_previa_termino']))->format('d/m/Y')); ?></p>
                <?php if (!empty($etapa['imagem'])): ?>
                    <img src="<?php echo htmlspecialchars($etapa['imagem'] ?? ''); ?>" alt="Imagem da Etapa" style="max-width: 100%; height: auto;">
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
    
    <div class="btn-group">
        <a href="proj.php" class="btn-custom">Voltar para Projetos</a>
    </div>
</main>
</body>
</html>
