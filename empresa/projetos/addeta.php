<?php
include '../../includes/db_connect.php';

if (isset($_GET['projeto_id'])) {
    $projeto_id = intval($_GET['projeto_id']);
} else {
    echo "Erro: projeto_id não especificado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? null;
    $descricao = $_POST['descricao'] ?? null;
    $observacoes = $_POST['observacoes'] ?? null;
    $data_inicio = $_POST['data_inicio'] ?? null;
    $data_termino = $_POST['data_termino'] ?? null;
    $data_previa_inicio = $_POST['data_previa_inicio'] ?? null;
    $data_previa_termino = $_POST['data_previa_termino'] ?? null;

    if (empty($titulo)) {
        echo "Erro: O título é obrigatório.";
        exit();
    }

    $sql = "INSERT INTO etapas (titulo, descricao, observacoes, projeto_id, data_inicio, data_termino, data_previa_inicio, data_previa_termino) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssiss", $titulo, $descricao, $observacoes, $projeto_id, $data_inicio, $data_termino, $data_previa_inicio, $data_previa_termino);

    if ($stmt->execute()) {
        $sql_update = "UPDATE projetos SET qtn_eta = qtn_eta + 1 WHERE projeto_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $projeto_id);
        $stmt_update->execute();

        header("Location: addeta.php?projeto_id=" . $projeto_id . "&message=Etapa adicionada com sucesso.");
        exit();
    } else {
        echo "Erro ao adicionar etapa: " . $stmt->error;
    }
}

$sql_etapas = "SELECT * FROM etapas WHERE projeto_id = ?";
$stmt_etapas = $conn->prepare($sql_etapas);
$stmt_etapas->bind_param("i", $projeto_id);
$stmt_etapas->execute();
$result_etapas = $stmt_etapas->get_result();

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>ObraPlanner</title>
    <style>
        h2 {
            color: #135545;
            text-align: center;
            margin-top: 5px;
            font-size: 1.8rem;
        }

        .form-container {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            padding: 40px;
            border-radius: 12px;
            margin: 20px auto;
            width: 90%;
            max-width: 750px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-weight: 600;
            color: #074470;
            display: block;
            margin-bottom: 5px;
        }

        form input[type="text"],
        form textarea,
        form input[type="date"] {
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 15px;
            width: 100%;
            margin-bottom: 15px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .date-container {
            display: flex;
            gap: 15px;
        }

        .date-container div:first-child {
            flex: 1;
            text-align: left;
        }

        .date-container div:last-child {
            flex: 1;
            text-align: right;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 25px;
        }

        .btnproj {
            background-color: #074470;
            color: #ffffff;
            border: solid 1px #074470;
            padding: 0.6rem 1.2rem;
            font-size: 1rem;
            border-radius: 5px;
            transition: background-color 0.2s, color 0.2s;
            text-decoration: none;
            text-align: center;
        }

        .btnproj:hover {
            background-color: #ffffff;
            color: #074470;
            border: solid 1px #074470;
        }

        .etapas-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .etapa-item {
            background-color: #ffffff;
            border: solid 1px #e0e0e0;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .etapa-item h3 {
            font-size: 1.2rem;
            color: #074470;
            margin-bottom: 10px;
        }

        .etapa-item .date-left {
            text-align: left;
            font-size: 0.95rem;
            color: #555;
            margin: 10px;
        }

        .etapa-item .date-right {
            text-align: right;
            font-size: 0.95rem;
            color: #555;
            margin: 10px;
        }

        .back-to-projects {
            display: flex;
            justify-content: center;
            margin-top: 20px;
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
<h2>Adicionar Etapa</h2>
    <div class="form-container">
        <form action="addeta.php?projeto_id=<?php echo $projeto_id; ?>" method="POST" enctype="multipart/form-data">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" required>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" required></textarea>

            <label for="observacoes">Observações:</label>
            <textarea name="observacoes"></textarea>

            <div class="date-container">
                <div>
                    <label for="data_inicio">Data de Início:</label>
                    <input type="date" name="data_inicio" required>
                </div>
                <div>
                    <label for="data_termino">Data de Término:</label>
                    <input type="date" name="data_termino" required>
                </div>
            </div>

            <div class="date-container">
                <div>
                    <label for="data_previa_inicio">Data Prévia de Início:</label>
                    <input type="date" name="data_previa_inicio">
                </div>
                <div>
                    <label for="data_previa_termino">Data Prévia de Término:</label>
                    <input type="date" name="data_previa_termino">
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="btnproj">Adicionar Etapa</button>
            </div>
        </form>
    </div>

    <h2>Etapas Adicionadas</h2>
    <div class="etapas-container">
        <?php while ($etapa = $result_etapas->fetch_assoc()): ?>
            <div class="etapa-item">
                <h3><?php echo htmlspecialchars($etapa['titulo']); ?></h3>
                <div class="date-left"><strong>Início:</strong> <?php echo htmlspecialchars($etapa['data_inicio']); ?></div>
                <div class="date-right"><strong>Término:</strong> <?php echo htmlspecialchars($etapa['data_termino']); ?></div>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="back-to-projects">
        <a href="proj.php" class="btnproj">Voltar aos Projetos</a>
    </div>
</main>
</body>
</html>
