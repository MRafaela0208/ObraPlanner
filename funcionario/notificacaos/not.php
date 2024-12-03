<?php
include '../../includes/db_connect.php';

$usu_id = 1;  
$tipo_usuario = 'funcionario';  

$sql = "SELECT tipo_notificacao, mensagem, data_criacao 
        FROM notificacoes 
        WHERE usu_id = ? AND tipo_usuario = ? 
        ORDER BY data_criacao DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $usu_id, $tipo_usuario);
$stmt->execute();
$result = $stmt->get_result();

$notificacoes = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>ObraPlanner</title>
</head>
<style>
.notifications-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 20px;
}

.notification-item {
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.notification-icon {
    font-size: 24px;
    margin-right: 15px;
    color: #007bff;
}

.notification-content {
    flex-grow: 1;
}

.notification-category {
    font-weight: bold;
    color: #074470;
}

.notification-time {
    color: #888;
    font-size: 0.9em;
}

.read-more {
    color: #007bff;
    cursor: pointer;
    font-size: 0.9em;
    text-decoration: underline;
}

.welcome { 
    display: flex;
    justify-content: space-between; 
    align-items: center; 
    margin-bottom: 20px; 
    background-color:#fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
    border-radius: 10px;
}

.welcome .message {
    flex: 1; 
    margin-right: 20px; 
    color: #000;
    font-weight: 500;
}
.message h2 {
    color:#135545;
    font-weight: 700;
}
.welcome img { 
    max-width: 100%; 
    height: auto;
    }
</style>
<body>
<header>
    <div class="logo">
        <img src="../../imgs/obraplanner7.png" alt="Logo do ObraPlanner">
    </div>
</header>
<nav class="sidebar">
    <ul>
        <li><a href="../dash.php"><i class="bx bx-home"></i> Home</a></li>
        <li><a href="../projetos/proj.php"><i class="bx bx-folder"></i> Projetos</a></li>
        <li><a href="not.php"><i class="bx bx-bell"></i> Notificações</a></li>
        <li><a href="../presenca/presenca.php"><i class="bx bx-task"></i> Presença</a></li>
        <li><a href="../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
        <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
    </ul>
</nav>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
    <?php if (!empty($notificacoes)): ?>
        <?php foreach ($notificacoes as $notificacao): ?>
            <div class="notification-item">
                <i class="bx bx-info-circle notification-icon"></i>
                <div class="notification-content">
                    <h4 class="notification-category"><?php echo ucwords(str_replace('_', ' ', $notificacao['tipo_notificacao'])); ?></h4>
                    <p><?php echo $notificacao['mensagem']; ?></p>
                    <span class="notification-time"><?php echo date('d/m/Y H:i', strtotime($notificacao['data_criacao'])); ?></span>
                </div>
                <span class="read-more">Ler mais</span>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="welcome">
            <div class="message">
                <h2>Nenhuma Notificação</h2>
                <p>Atualmente, você não possui notificações. Mas não se preocupe! Fique atento às atualizações e comentários relacionados aos seus projetos. Assim que houver novidades, você será o primeiro a saber!</p>
            </div>
            <img src="../imgs/not.png" alt="Nenhuma Notificação">
        </div>
    <?php endif; ?>
</main>
</body>
</html>
