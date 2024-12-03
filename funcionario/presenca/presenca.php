<?php
include '../../includes/db_connect.php';
session_start();

date_default_timezone_set('America/Sao_Paulo');

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

$data = date('Y-m-d'); 
$checkin = date('Y-m-d H:i:s'); 

if (isset($_POST['checkin'])) {
    $stmt = $con->prepare("INSERT INTO presenca (func_id, data, checkin) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $func_id, $data, $checkin);
    $stmt->execute();
}

if (isset($_POST['checkout'])) {
    $checkout = date('Y-m-d H:i:s'); 
    $stmt = $con->prepare("UPDATE presenca SET checkout = ? WHERE func_id = ? AND data = ?");
    $stmt->bind_param("sis", $checkout, $func_id, $data);
    $stmt->execute();

    $stmt = $con->prepare("SELECT checkin FROM presenca WHERE func_id = ? AND data = ?");
    $stmt->bind_param("is", $func_id, $data);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $presenca = $result->fetch_assoc();
        $checkin_time = new DateTime($presenca['checkin']);
        $checkout_time = new DateTime($checkout);
        $interval = $checkin_time->diff($checkout_time);

        $horas_trabalhadas = $interval->h + ($interval->i / 60);

        $stmt = $con->prepare("SELECT horas_trabalhadas FROM funcionarios WHERE func_id = ?");
        $stmt->bind_param("i", $func_id);
        $stmt->execute();
        $query = $stmt->get_result();
        $funcionario = $query->fetch_assoc();

        $total_horas_trabalhadas = $funcionario['horas_trabalhadas'] ? $funcionario['horas_trabalhadas'] : 0;

        $total_horas_trabalhadas += $horas_trabalhadas;

        $stmt = $con->prepare("UPDATE funcionarios SET horas_trabalhadas = ? WHERE func_id = ?");
        $stmt->bind_param("di", $total_horas_trabalhadas, $func_id);
        $stmt->execute();
    }
}

if (isset($_POST['delete_all'])) {
    $stmt = $con->prepare("DELETE FROM presenca WHERE func_id = ?");
    $stmt->bind_param("i", $func_id);
    $stmt->execute();
}

if (isset($_POST['delete_one'])) {
    $record_id = $_POST['record_id'];
    $stmt = $con->prepare("DELETE FROM presenca WHERE presenca_id = ?");
    $stmt->bind_param("i", $record_id);
    $stmt->execute();
}

$sql = "SELECT * FROM presenca WHERE func_id = ? ORDER BY data DESC";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $func_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>ObraPlanner</title>
</head>
<style>
    .no-projects {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 30px;
        border-radius: 10px;
    }
    .no-projects h2 {
        color: #135545;
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
        <li><a href="../projetos/proj.php"><i class="bx bx-folder"></i> Projetos</a></li>
        <li><a href="../notificacaos/not.php"><i class="bx bx-bell"></i> Notificações</a></li>
        <li><a href="presenca.php"><i class="bx bx-task"></i> Presença</a></li>
        <li><a href="../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
        <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
    </ul>
</nav>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
<div class="no-projects">
        <div>
            <h2>Bem-vindo à área de Registro de Presença!</h2>
            <p>Você está na seção de Registro de Presença, onde pode facilmente gerenciar seu horário de trabalho. Aqui, você pode registrar seus check-ins e check-outs diários.</p>
        </div>
        <div>
            <img src="../imgs/projetos5.png" alt="Imagem de boas-vindas">
        </div>
</div>
 
<section class="presenca">
        <h2>Registro de Presença</h2>
        <div class="buttons">
            <form method="POST" action="">
                <button type="submit" name="checkin" id="checkin-btn" class="btn">Check-in</button>
                <button type="submit" name="checkout" id="checkout-btn" class="btn">Check-out</button>
                <button type="submit" name="delete_all" class="btn btn-danger">Deletar Todos</button>
            </form>
        </div>
    </section>
    <section class="historico">
        <h2>Histórico de Presença</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($row['data'])); ?></td>
                        <td><?php echo date('H:i', strtotime($row['checkin'])); ?></td>
                        <td><?php echo $row['checkout'] ? date('H:i', strtotime($row['checkout'])) : 'Ainda não registrado'; ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="record_id" value="<?php echo $row['presenca_id']; ?>">
                                <button type="submit" name="delete_one" class="btn btn-danger">
                                    <i class="bx bx-trash"></i> </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>

<?php
$con->close();
?>
