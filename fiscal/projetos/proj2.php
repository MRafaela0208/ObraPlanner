<?php
include_once '../../includes/db_connect.php';
session_start();

$projeto_id = $_GET['projeto_id']; 

$query_project = "SELECT * FROM projetos WHERE projeto_id = ?";
$stmt_proj = $conn->prepare($query_project);
$stmt_proj->bind_param("i", $projeto_id);
$stmt_proj->execute();
$projeto = $stmt_proj->get_result()->fetch_assoc();

if (!$projeto) {
    echo "Projeto não encontrado.";
    exit;
}

$query = "SELECT * FROM etapas WHERE projeto_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $projeto_id);
$stmt->execute();
$etapas = $stmt->get_result();

$progresso_atual = $projeto['progresso'];
$status_atual = $projeto['status'];
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
    h1{
        color: #135545;
        text-align: center;
    }
.btn-group {
    display: flex;
    gap: 10px;
    justify-content: center; 
    margin-top: 20px;
}

.btn-custom {
    background-color: white;
    color:#074470 ;
    border: solid 1px #074470;
    padding: 10px 20px;
    font-size: 1em;
    border-radius: 10px;
    cursor: pointer;
    text-decoration: none; 
    transition: background-color 0.3s ease;
}

.btn-custom:hover {
    background-color:#074470;
    color:white;
}

.progresso-status {
    display: flex;
    justify-content: center;
    gap: 20px;
    font-size: 1.2em;
    text-align: center;
    margin-bottom: 20px;
}
.progresso-status p strong{
    color: # #274a70;
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

.etapa-item label {
    font-weight: bold;
    margin-left: 5px;
    color: #003d7a;
}

.etapa-item p {
    margin: 5px 0;
}
.etapa-item p strong{
    margin: 5px 0;
    color: #135545;
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
        <li><a href="../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
        <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
    </ul>
</nav>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
<h1><?php echo htmlspecialchars($projeto['titulo']); ?></h1>
<div class="progresso-status">
    <p><strong>Status atual:</strong> <span id="status"><?php echo htmlspecialchars($status_atual); ?></span></p>
    <p><strong>Progresso:</strong> <span id="progresso"><?php echo htmlspecialchars($progresso_atual); ?>%</span></p>
</div>

<form action="proj3.php" method="POST">
    <input type="hidden" name="projeto_id" value="<?php echo $projeto_id; ?>">
    <input type="hidden" id="input-progresso" name="progresso">
    <input type="hidden" id="input-status" name="status">

    <div class="etapas-container">
    <?php while ($etapa = $etapas->fetch_assoc()) { ?>
        <div class="etapa-item">
            <?php if ($etapa['concluida']) { ?>
                <i class="bx bx-check-circle" style="color: green;"></i>
                <label><?php echo htmlspecialchars($etapa['titulo']); ?></label>
            <?php } else { ?>
                <input type="checkbox" class="etapa-checkbox" name="etapas[]" value="<?php echo $etapa['etapa_id']; ?>" onclick="calcularProgresso()">
                <label for="etapa_<?php echo $etapa['etapa_id']; ?>"><?php echo htmlspecialchars($etapa['titulo']); ?></label>
            <?php } ?>
            <p><strong>Descrição:</strong> <?php echo htmlspecialchars($etapa['descricao']); ?></p>
            <p><strong>Observação:</strong> <?php echo htmlspecialchars($etapa['observacoes']); ?></p>
            <p><strong>Data de Início:</strong> 
            <?php echo htmlspecialchars((new DateTime($etapa['data_inicio']))->format('d/m/Y')); ?></p>
            <p><strong>Data de Término:</strong> 
            <?php echo htmlspecialchars((new DateTime($etapa['data_termino']))->format('d/m/Y')); ?></p>
        </div>
    <?php } ?>
</div>

<div class="btn-group">
    <button type="submit" class="btn-custom">Atualizar Progresso</button>
    <button type="button" class="btn-custom" onclick="window.location.href='proj.php'">Voltar</button>
</div>
</form>

<script>
function calcularProgresso() {
    let checkboxes = document.querySelectorAll('.etapa-checkbox');
    let totalEtapas = checkboxes.length;
    let etapasConcluidas = 0;

    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            etapasConcluidas++;
        }
    });

    let porcentagem = (etapasConcluidas / totalEtapas) * 100;
    document.getElementById('progresso').innerText = porcentagem.toFixed(2) + '%';

    let status = '';
    if (porcentagem === 100) {
        status = 'Concluído';
    } else if (porcentagem > 0 && porcentagem < 100) {
        status = 'Em andamento';
    } else {
        status = 'Atrasado';
    }
    document.getElementById('status').innerText = status;

    document.getElementById('input-progresso').value = porcentagem.toFixed(2);
    document.getElementById('input-status').value = status;
}
</script>
</main>
</body>
</html>
