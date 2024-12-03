<?php 
include '../../includes/db_connect.php';

$projeto_id = $_GET['id'];

$sql = "SELECT * FROM projetos WHERE projeto_id = $projeto_id";
$result = $conn->query($sql);
$projeto = $result->fetch_assoc();

$sqlEtapas = "SELECT * FROM etapas WHERE projeto_id = $projeto_id";
$resultEtapas = $conn->query($sqlEtapas);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>ObraPlanner</title> 
    <style>
        .btnproj {
            padding: 0.1rem 0.5rem;
            font-size: 1rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block; 
        }
        .container {
            margin-top: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            background-color: #fff;
            margin-bottom: 20px;
        }
        h2 {
            color: #135545;
        }
        h3 {
            margin-top: 20px;
            color: #324363;
        }
        h4 {
            color: #324363;
        }
        .btnback, .btnverdetalhes {
            background-color: #fff;
            color: #074470;
            border: solid 1px #074470;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block; 
        }
        .btnback:hover, .btnverdetalhes:hover {
            background-color: #074470;
            color: #fff;
            border: solid 1px #074470;      
        }
        .btnadd {
            background-color: #fff;
            color: #074470;
            border: solid 1px #074470;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block; 
        }
        .btnadd:hover {
            background-color: #074470;
            color: #fff;
            border: solid 1px #074470;      
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
        <h2><?php echo htmlspecialchars($projeto['titulo']); ?></h2>
        <p><?php echo htmlspecialchars($projeto['descricao']); ?></p>
        
        <h3>Etapas</h3>
        
        <a href="addeta.php?projeto_id=<?php echo $projeto['projeto_id']; ?>" class="btnadd"><i class="bx bx-plus-circle"></i> Adicionar Etapa</a>
        <a href="proj.php" class="btnback">Voltar</a>
        
        <div class="row">
        <?php 
        if ($resultEtapas && $resultEtapas->num_rows > 0) {
            while ($etapa = $resultEtapas->fetch_assoc()) {
                ?>
                <div class="col-md-4">
                    <div class="container">
                        <h4><?php echo htmlspecialchars($etapa['titulo']); ?></h4>
                        <p><?php echo htmlspecialchars($etapa['descricao']); ?></p>
                        
                        <div>
                            <a href="editeta.php?id=<?php echo $etapa['etapa_id']; ?>" class="btnproj" style="color: #2196F3;"> 
                            <i class='bx bx-pencil'></i> Editar</a>
                            <a href="deleta.php?id=<?php echo $etapa['etapa_id']; ?>" class="btnproj"style="color: #F44336;"> 
                            <i class='bx bx-trash'></i> Excluir</a>
                            <button class="btnproj" onclick="toggleDetails('<?php echo $etapa['etapa_id']; ?>')"style="color: #4CAF50; background-color:#fff; border:none;">
                            <i class="bx bx-show"></i></button>
                        </div>

                        <div id="details-<?php echo $etapa['etapa_id']; ?>" style="display:none; margin-top: 10px;">
                            <p><strong>Observações:</strong> <?php echo htmlspecialchars($etapa['observacoes']); ?></p>
                            <p><strong>Data Prévia de Início:</strong> <?php echo date('d/m/Y', strtotime($etapa['data_previa_inicio'])); ?></p>
                            <p><strong>Data Prévia de Término:</strong> <?php echo date('d/m/Y', strtotime($etapa['data_previa_termino'])); ?></p>
                            <p><strong>Data de Início:</strong> <?php echo date('d/m/Y', strtotime($etapa['data_inicio'])); ?></p>
                            <p><strong>Data de Término:</strong> <?php echo date('d/m/Y', strtotime($etapa['data_termino'])); ?></p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="col-md-4">
                <div class="container">
                    <p>Nenhuma etapa criada para este projeto.</p>
                </div>
            </div>
            <?php
        }
        ?>
        </div>
    </main>

    <script>
        function toggleDetails(etapaId) {
            const details = document.getElementById(`details-${etapaId}`);
            if (details.style.display === "none") {
                details.style.display = "block";
            } else {
                details.style.display = "none";
            }
        }
    </script>
</body>
</html>
