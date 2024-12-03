<?php
session_start();

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$projeto_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; 

$conn = new mysqli('localhost', 'root', '', 'obra_planner');

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$sql = "SELECT * FROM projetos WHERE projeto_id = ? AND empresa_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $projeto_id, $_SESSION['UsuarioID']);
$stmt->execute();
$result = $stmt->get_result();
$projeto = $result->fetch_assoc();

if (!$projeto) {
    echo "<p>Projeto não encontrado ou você não tem permissão para acessá-lo.</p>";
    exit();
}

$fiscal_sql = "SELECT fiscal_id, nome FROM fiscais WHERE empresa_id = ?";
$fiscal_stmt = $conn->prepare($fiscal_sql);
$fiscal_stmt->bind_param("i", $_SESSION['UsuarioID']);
$fiscal_stmt->execute();
$fiscais = $fiscal_stmt->get_result();
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
            font-size: 2rem; 
            margin-bottom: 20px;
        }

        .form-container {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            padding: 40px;
            border-radius: 15px; 
            margin: 20px auto;
            width: 90%;
            max-width: 800px; 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); 
        }

        form label {
            font-weight: 600;
            color: #074470;
            display: block;
            margin-bottom: 8px;
        }

        form input[type="text"],
        form textarea,
        form input[type="date"],
        form input[type="file"],
        form select {
            padding: 14px; 
            border: 1px solid #ced4da;
            border-radius: 8px; 
            font-size: 16px; 
            width: 100%;
            margin-bottom: 20px; 
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1); }

        .date-container {
            display: flex;
            justify-content: space-between;
            gap: 15px; 
        }

        .date-container input[type="date"] {
            flex: 1;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 30px; 
            gap: 25px; 
        }

        .btnproj {
            background-color: #074470;
            color: #ffffff;
            border: none; 
            padding: 12px 35px; 
            font-size: 1.1rem; 
            border-radius: 8px;
            transition: background-color 0.3s, color 0.3s, transform 0.2s;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
        }

        .btnproj:hover {
            background-color: #ffffff;
            color: #074470;
            border: solid 1px #074470;
            transform: translateY(-2px); 
        }
        .file-upload {
        display: flex;
        align-items: center;
        margin-top: 15px;
        cursor: pointer;
        color: #074470;
        gap: 10px; 
        position: relative;
    }
    .file-upload input[type="file"] {
        display: none; 
    }
    .file-status {
        color: green; 
        margin-left: 10px; 
    }
    .upload-container {
        display: flex;
        justify-content: space-between; 
    }

    .upload-container label {
        flex: 1;
        margin: 0 10px; 
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
    <h2>Editar Projeto</h2>
    <form action="editproj2.php" method="post" enctype="multipart/form-data" class="form-container">
        <input type='hidden' name='projeto_id' value='<?php echo htmlspecialchars($projeto['projeto_id']); ?>' required>
        <label for='titulo'>Título:</label>
        <input type='text' name='titulo' value='<?php echo htmlspecialchars($projeto['titulo']); ?>' required>
        
        <label for='descricao'>Descrição:</label>
        <textarea name='descricao' required><?php echo htmlspecialchars($projeto['descricao']); ?></textarea>
        
        <div class='date-container'>
            <div>
                <label for='data_inicio'>Data de Início:</label>
                <input type='date' name='data_inicio' value='<?php echo htmlspecialchars($projeto['data_inicio']); ?>' required>
            </div>
            <div>
                <label for='data_termino'>Data de Término:</label>
                <input type='date' name='data_termino' value='<?php echo htmlspecialchars($projeto['data_termino']); ?>' required>
            </div>
        </div>
        
        <div class='date-container'>
            <div>
                <label for='data_prev_ini'>Data Prevista de Início:</label>
                <input type='date' name='data_prev_ini' value='<?php echo htmlspecialchars($projeto['data_prev_ini']); ?>' required>
            </div>
            <div>
                <label for='data_prev_ter'>Data Prevista de Término:</label>
                <input type='date' name='data_prev_ter' value='<?php echo htmlspecialchars($projeto['data_prev_ter']); ?>' required>
            </div>
        </div>
        
        <label for='fiscal_id'>Fiscal Responsável:</label>
        <select name='fiscal_id' required>
            <option value='' disabled selected>Selecione um fiscal</option>
            <?php while ($fiscal = $fiscais->fetch_assoc()): ?>
                <option value="<?php echo $fiscal['fiscal_id']; ?>" <?php echo ($fiscal['fiscal_id'] == $projeto['fiscal_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($fiscal['nome']); ?>
                </option>
            <?php endwhile; ?>
        </select>
<div class="upload-container">
    <label class="file-upload" id="imagem-upload">
        <i class='bx bx-upload'></i>
        <span>Imagem:</span>
        <input type="file" name="imagem" accept="image/*" onchange="handleFileUpload(this, 'imagem-status')">
        <div class="file-status" id="imagem-status"></div>
    </label>

    <label class="file-upload" id="documento-upload">
        <i class='bx bx-upload'></i>
        <span>Documentação:</span>
        <input type="file" name="documento" accept="application/pdf" onchange="handleFileUpload(this, 'documento-status')">
        <div class="file-status" id="documento-status"></div>
    </label>
</div>
<div class="button-container">
            <button type="submit" class="btnproj">Salvar</button>
            <a href="proj.php" class="btnproj">Cancelar</a>
        </div>
    </form>
</main>
<script>
    function handleFileUpload(input, statusId) {
        const statusDiv = document.getElementById(statusId);
        const fileName = input.files[0] ? input.files[0].name : '';
        if (fileName) {
            statusDiv.textContent = "Arquivo adicionado: " + fileName;
        } else {
            statusDiv.textContent = ""; 
        }
    }
</script>
</body>
</html>
