<?php 
include '../../includes/db_connect.php';

if (isset($_GET['id'])) {
    $etapa_id = intval($_GET['id']);
    $sql = "SELECT * FROM etapas WHERE etapa_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $etapa_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $etapa = $result->fetch_assoc();
    } else {
        echo "Etapa não encontrada.";
        exit();
    }
} else {
    echo "ID da etapa não especificado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
        form input[type="date"],
        form select {
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 15px;
            width: 100%;
            margin-bottom: 15px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .file-upload {
            position: relative;
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 15px;
        }

        .file-upload input[type="file"] {
            opacity: 0;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .upload-container {
            display: flex;
            justify-content: space-between;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 25px;
            gap: 20px;
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
        .form-group {
    display: flex;
    justify-content: space-between; 
    margin-bottom: 20px;}

.date-container {
    flex: 1; 
    margin: 0 10px;
}

label {
    display: block; 
    margin-bottom: 5px; 
}

input[type="date"] {
    width: 100%; 
    padding: 10px; 
    border: 1px solid #ccc;
    border-radius: 5px;
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
        <h2>Editar Etapa</h2>
        <div class="form-container">
            <form action="editeta2.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="etapa_id" value="<?php echo $etapa['etapa_id']; ?>">
                
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($etapa['titulo']); ?>" required>

                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" required><?php echo htmlspecialchars($etapa['descricao']); ?></textarea>

                <label for="observacoes">Observações</label>
                <textarea id="observacoes" name="observacoes"><?php echo htmlspecialchars($etapa['observacoes']); ?></textarea>

                <div class="form-group">
    <div class="date-container">
        <label for="pre-start-date">Data Prévia de Início:</label>
        <input type="date" id="data_previa_inicio" name="data_previa_inicio" value="<?php echo $etapa['data_previa_inicio']; ?>">
        </div>
    <div class="date-container">
        <label for="start-date">Data de Início:</label>
        <input type="date" id="data_inicio" name="data_inicio" value="<?php echo $etapa['data_inicio']; ?>" required>
        </div>
</div>
<div class="form-group">
    <div class="date-container">
        <label for="pre-end-date">Data Prévia de Término:</label>
        <input type="date" id="data_previa_termino" name="data_previa_termino" value="<?php echo $etapa['data_previa_termino']; ?>">
        </div>
    <div class="date-container">
        <label for="end-date">Data de Término:</label>
        <input type="date" id="data_termino" name="data_termino" value="<?php echo $etapa['data_termino']; ?>" required>
        </div>
</div>

                <div class="button-container">
                    <button type="submit" class="btnproj">Atualizar Etapa</button>
                    <a href="verproj.php?id=<?php echo $etapa['projeto_id']; ?>" class="btnproj">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
