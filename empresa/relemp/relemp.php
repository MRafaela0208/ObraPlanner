<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); 

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$empresa_id = $_SESSION['UsuarioID'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "obra_planner";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}

$queryProgresso = "SELECT titulo, progresso FROM projetos WHERE empresa_id = :empresa_id";
$stmtProgresso = $conn->prepare($queryProgresso);
$stmtProgresso->bindParam(':empresa_id', $empresa_id);
$stmtProgresso->execute();
$progressoProjetos = $stmtProgresso->fetchAll(PDO::FETCH_ASSOC);

$queryFunc = "SELECT nome, horas_trabalhadas FROM funcionarios WHERE empresa_id = :empresa_id";
$stmtFunc = $conn->prepare($queryFunc);
$stmtFunc->bindParam(':empresa_id', $empresa_id);
$stmtFunc->execute();
$funcionarios = $stmtFunc->fetchAll(PDO::FETCH_ASSOC);

$queryDatas = "SELECT titulo, data_prev_ter, data_termino FROM projetos WHERE empresa_id = :empresa_id";
$stmtDatas = $conn->prepare($queryDatas);
$stmtDatas->bindParam(':empresa_id', $empresa_id);
$stmtDatas->execute();
$datas = $stmtDatas->fetchAll(PDO::FETCH_ASSOC);

$temProjetos = !empty($progressoProjetos);
$temFuncionarios = !empty($funcionarios);
$temDatas = !empty($datas);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>ObraPlanner</title>
   <style>
    .h2 {
    text-align: center;
    color: #074470;
    font-size: 1.6em;
    margin-top: 10px;
}

.report {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #fff; 
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 30px;
}

canvas {
    max-width: 100%;
    height: 350px;
    border-radius: 8px;
}

.report p {
    font-size: 0.95em;
    color: #555;
    text-align: center;
    margin-top: 10px;
}

.read-more {
    color: #074470;
    font-size: 14px;
    text-decoration: underline;
    cursor: pointer;
}

.welcome {
    display: flex;
    justify-content: space-between; 
    align-items: center; 
    margin-bottom: 20px; 
    background-color: #fff;
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
    color: #135545;
    font-weight: 700;
}

.welcome img {
    max-width: 100%; 
    height: auto;
}

.btn-relatorio {
    background-color: #074470;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 20px;
    transition: background-color 0.3s;
}

.btn-relatorio:hover {
    background-color: #056094;
}

.btn-relatorio:focus {
    outline: none;
}

#progresso-produtividade {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

#comparacao-datas {
    margin-top: 30px;
}

@media (max-width: 768px) {
    #progresso-produtividade {
        flex-direction: column;
    }

    .col-md-6 {
        margin-bottom: 20px;
    }

    .welcome {
        flex-direction: column;
        text-align: center;
    }

    .welcome img {
        width: 100%;
        margin-top: 20px;
    }
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
        <li><a href="../fiscal/fis.php"><i class="bx bx-user"></i> Fiscais</a></li>
        <li><a href="../funcionarios/func.php"><i class="bx bx-user"></i> Funcionários</a></li>
        <li><a href="../notificacaos/not.php"><i class="bx bx-bell"></i> Notificações</a></li>
        <li><a href="relemp.php"><i class="bx bx-task"></i> Relatórios</a></li>
        <li><a href="../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
        <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
    </ul>
</nav>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
    <div class="welcome">
        <div class="message">
            <h2>Bem-vindo à Central de Relatórios!</h2>
            <p>Comece a acompanhar o progresso dos seus projetos e a produtividade da sua equipe. Esta área ajuda você a visualizar todas as métricas essenciais para manter o desempenho em alta!</p>
        </div>
        <img src="../imgs/rel.png" alt="Bem-vindo ao ObraPlanner">
    </div>

    <?php if ($temProjetos || $temFuncionarios || $temDatas): ?>
        <section id="progresso-produtividade">
        <div class="col-md-6">
            <h2 class="h2">Progresso dos Projetos</h2>
            <div class="report" style="flex: 1; margin-right: 20px;">
                <canvas id="graficoProgresso"></canvas>
                <script>
                    const ctxProgresso = document.getElementById('graficoProgresso').getContext('2d');
                    const graficoProgresso = new Chart(ctxProgresso, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode(array_column($progressoProjetos, 'titulo')); ?>,
                            datasets: [{
                                label: 'Progresso (%)',
                                data: <?php echo json_encode(array_column($progressoProjetos, 'progresso')); ?>,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100
                                }
                            }
                        }
                    });
                </script>
                <p>Visualize o progresso atual de cada projeto.</p>
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="h2">Comparação de Datas</h2>
            <div class="report">
                <canvas id="graficoDatas"></canvas>
                <script>
                    const ctxDatas = document.getElementById('graficoDatas').getContext('2d');
                    const graficoDatas = new Chart(ctxDatas, {
                        type: 'line',
                        data: {
                            labels: <?php echo json_encode(array_column($datas, 'titulo')); ?>,
                            datasets: [{
                                label: 'Data Prevista para Término',
                                data: <?php echo json_encode(array_column($datas, 'data_prev_ter')); ?>,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                fill: false
                            },
                            {
                                label: 'Data de Término Real',
                                data: <?php echo json_encode(array_column($datas, 'data_termino')); ?>,
                                borderColor: 'rgba(153, 102, 255, 1)',
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                fill: false
                            }]
                        }
                    });
                </script>
                <p style="font-size: 14px;">Compare as datas previstas com as reais para avaliar a pontualidade dos projetos.</p>
            </div>
        </div>
        </section>
        <section id="comparacao-datas">
    <div class="col-md-12">
        <h2 class="h2">Produtividade dos Funcionários</h2>
        <div class="report" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <canvas id="graficoProdutividade" style="max-width: 100%; height: 400px;"></canvas>
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; margin-top: 20px;">
            <p>Acompanhe a produtividade de cada funcionário.</p>
                </div>
        </div>
    </div>
</section>
<script>
const ctxProdutividade = document.getElementById('graficoProdutividade').getContext('2d');
const graficoProdutividade = new Chart(ctxProdutividade, {
    type: 'bar', 
    data: {
        labels: <?php echo json_encode(array_column($funcionarios, 'nome')); ?>, 
        datasets: [{
            label: 'Horas Trabalhadas',
            data: <?php echo json_encode(array_column($funcionarios, 'horas_trabalhadas')); ?>, 
            backgroundColor: '#36A2EB',
            borderColor: '#1E78A3',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 5,
                },
                title: {
                    display: true,
                    text: 'Horas Trabalhadas'
                }
            },
            x: {
                title: {
                    display: true,
                },
                ticks: {
                    autoSkip: false,
                    maxRotation: 0, 
                    minRotation: 0
                }
            }
        }
    }
});
</script>
    <?php else: ?>
        <p class="text-center">Não há dados disponíveis para exibir.</p>
    <?php endif; ?>
</main>
</body>
</html>
