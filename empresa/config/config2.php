<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../includes/db_connect.php';

session_start();

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php"); 
    exit();
}

$empresa_id = $_SESSION['UsuarioID'];

$query = "SELECT * FROM empresas WHERE empresa_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $empresa_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $empresa = $result->fetch_assoc();
} else {
    echo "Empresa não encontrada.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $cnpj = $_POST['cnpj'];
    $responsavel = $_POST['responsavel'];

    if (!empty($_POST['nova_senha']) && !empty($_POST['confirmar_senha'])) {
        $nova_senha = $_POST['nova_senha'];
        $confirmar_senha = $_POST['confirmar_senha'];

        if ($nova_senha === $confirmar_senha) {
            $hash_senha = password_hash($nova_senha, PASSWORD_BCRYPT);

            $query_senha = "UPDATE empresas SET senha = ? WHERE empresa_id = ?";
            $stmt_senha = $conn->prepare($query_senha);
            $stmt_senha->bind_param("si", $hash_senha, $empresa_id);

            if ($stmt_senha->execute()) {
                echo "Senha atualizada com sucesso!";
            } else {
                echo "Erro ao atualizar a senha.";
            }
        } else {
            echo "As senhas não coincidem.";
            exit();
        }
    }

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/logos/";
        $target_file = $target_dir . basename($_FILES["logo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (getimagesize($_FILES["logo"]["tmp_name"])) {
            if (file_exists($target_file)) {
                echo "Desculpe, o arquivo já existe.";
            } else {
                if ($_FILES["logo"]["size"] > 500000) {
                    echo "Desculpe, o arquivo é muito grande.";
                } else {
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        echo "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
                    } else {
                        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
                            $logo = basename($_FILES["logo"]["name"]);
                            $query_logo = "UPDATE empresas SET logo = ? WHERE empresa_id = ?";
                            $stmt_logo = $conn->prepare($query_logo);
                            $stmt_logo->bind_param("si", $logo, $empresa_id);

                            if ($stmt_logo->execute()) {
                                echo "Logo atualizada com sucesso!";
                            } else {
                                echo "Erro ao atualizar logo.";
                            }
                        } else {
                            echo "Desculpe, ocorreu um erro ao carregar seu arquivo.";
                        }
                    }
                }
            }
        } else {
            echo "Desculpe, apenas imagens são permitidas.";
        }
    }

    $query = "UPDATE empresas SET nome = ?, email = ?, telefone = ?, endereco = ?, cnpj = ?, responsavel = ? WHERE empresa_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $nome, $email, $telefone, $endereco, $cnpj, $responsavel, $empresa_id);

    if ($stmt->execute()) {
        echo "Informações atualizadas com sucesso!";
        header("Location: config.php");
        exit();
    } else {
        echo "Erro ao atualizar informações.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Obra Planner</title>
    <style>
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 800px;
            width: 100%;
        }
        h1 {
            color: #135545;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex; 
            align-items: center; 
        }
        .form-group label {
            font-weight: bold;
            color: #074470;
            margin-right: 10px; 
            white-space: nowrap;
        }
        .editable {
            cursor: pointer;
            color: #000; 
            border-bottom: none; 
        }
        .form-group img {
            max-height: 110px;
            border-radius: 50%;
            width: 110px;
            margin-bottom: 10px;
            display: block; 
            margin-left: auto; 
            margin-right: auto; 
            border: 2px solid #074470;
            cursor: pointer;
        }
        .form-control-file {
            display: none; 
        }
        .custom-file-upload {
            display: block;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
            color: #074470;
            padding: 5px;
            cursor: pointer;
        }
        .btnproj {
            background-color: #fff;
            color: #074470;
            border: solid 1px #074470;
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
            border-radius: 20px;
            text-decoration: none;
            display: inline-block;
            text-align: center; 
        }
        .btnproj:hover {
            background-color: #074470;
            color: #fff;
            border: solid 1px #074470;      
        }
        .btnback {
            background-color: #fff;
            color: #541313;
            border: solid 1px #541313;
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
            border-radius: 20px;
            text-decoration: none;
            display: inline-block;
            text-align: center; 
        }
        .btnback:hover {
            background-color: #541313;
            color: #fff;
            border: solid 1px #541313;      
        }
        .button-container {
            display: flex;
            justify-content: space-between; 
            margin-top: 20px; 
        }
        .form-group input[type="file"] {
            display: none;
        }
    </style>
    <script>
        function previewLogo(event) {
            const image = document.getElementById('imgPreview');
            image.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
</head>
<body>
<header>
    <div class="logo" style="text-align: center;">
        <img src="../../imgs/obraplanner7.png" alt="Logo do ObraPlanner">
    </div>
</header>
    <nav class="sidebar">
    <ul>
        <li><a href="../dash.php"><i class="bx bx-home"></i> Home</a></li>
        <li><a href="../projetos/proj.php"><i class="bx bx-folder"></i> Projetos</a></li>
        <li><a href="../fiscal/fis.php"><i class="bx bx-user"></i> Fiscais</a></li>
        <li><a href="../funcionarios/func.php"><i class="bx bx-user"></i> Funcionários</a></li>
        <li><a href="../notificacaos/not.php"><i class="bx bx-bell"></i> Notificações</a></li>
        <li><a href="../relemp/relemp.php"><i class="bx bx-task"></i> Relatórios</a></li>
        <li><a href="config.php"><i class="bx bx-cog"></i> Configurações</a></li>
        <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
    </ul>
</nav>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
    <div class="container">
        <h1>Configurações da Empresa</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group" style="display: flex; justify-content: center; align-items: center; text-align: center;">
                <?php if (!empty($empresa['logo'])): ?>
                    <label class="custom-file-upload">
                        <img id="imgPreview" src="../../uploads/logos/<?php echo htmlspecialchars($empresa['logo']); ?>" alt="Logo da Empresa" style="width: 110px; height: 100px; object-fit: cover;">
                        <input type="file" id="logo" name="logo" accept="image/*" onchange="previewLogo(event)">
                    </label>
                <?php else: ?>
                    <label class="custom-file-upload">
                        <img id="imgPreview" src="../../imgs/do-utilizador (1).png" alt="Imagem prévia" style="width: 110px; height: 100px; object-fit: cover;">
                        <input type="file" id="logo" name="logo" accept="image/*" onchange="previewLogo(event)">
                    </label>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Nome:</label>
                <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($empresa['nome']); ?>" required>
            </div>
            <div class="form-group">
                <label>E-mail:</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($empresa['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Telefone:</label>
                <input type="text" name="telefone" class="form-control" value="<?php echo htmlspecialchars($empresa['telefone']); ?>">
            </div>
            <div class="form-group">
                <label>Endereço:</label>
                <input type="text" name="endereco" class="form-control" value="<?php echo htmlspecialchars($empresa['endereco']); ?>" required>
            </div>
            <div class="form-group">
                <label>CNPJ:</label>
                <input type="text" name="cnpj" class="form-control" value="<?php echo htmlspecialchars($empresa['cnpj']); ?>" required>
            </div>
            <div class="form-group">
                <label>Responsável:</label>
                <input type="text" name="responsavel" class="form-control" value="<?php echo htmlspecialchars($empresa['responsavel']); ?>" required>
            </div>
            <div class="form-group">
                <label>Nova Senha:</label>
                <input type="password" name="nova_senha" class="form-control">
            </div>
            <div class="form-group">
                <label>Confirmar Nova Senha:</label>
                <input type="password" name="confirmar_senha" class="form-control">
            </div>
            <div class="button-container">
                <button type="button" class="btnback" onclick="window.location.href='config.php';">Cancelar</button>
                <button type="submit" class="btnproj">Salvar</button>
            </div>
        </form>
    </div>
</main>
</body>
</html>
