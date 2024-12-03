<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../includes/db_connect.php';

session_start();

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php"); 
    exit();
}

$usuario_id = $_SESSION['UsuarioID'];

$query = "SELECT * FROM funcionarios WHERE func_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $funcionario = $result->fetch_assoc();
} else {
    echo "Funcionário não encontrado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    
    if (!empty($_POST['senha'])) {
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); 
    } else {
        $senha = $funcionario['senha']; 
    }

    $query = "UPDATE funcionarios SET nome = ?, email = ?, telefone = ?, cpf = ?, senha = ? WHERE func_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $nome, $email, $telefone, $cpf, $senha, $usuario_id);
    
    if ($stmt->execute()) {
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../../uploads/fotos/";
            $target_file = $target_dir . basename($_FILES["foto"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["foto"]["tmp_name"]);
            if ($check === false) {
                echo "O arquivo não é uma imagem.";
                exit();
            }

            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                $query = "UPDATE funcionarios SET foto = ? WHERE func_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("si", basename($_FILES["foto"]["name"]), $usuario_id);
                $stmt->execute();
            } else {
                echo "Erro ao fazer upload da imagem.";
            }
        }
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>ObraPlanner</title>
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
        .form-group img, .form-group .user-icon {
            max-height: 120px;
            border-radius: 50%;
            width: 110px;
            margin-bottom: 10px;
            display: block; 
            margin-left: auto; 
            margin-right: auto; 
            cursor: pointer; 
        }
        .form-group .user-icon {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 90px;
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
        .btnproj, .btnback {
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
            display: inline-block;
            text-align: center; 
            text-decoration: none;
        }
        .btnproj {
            background-color: #fff;
            color: #074470;
            border: solid 1px #074470;
        }
        .btnproj:hover {
            background-color: #074470;
            color: #fff;
        }
        .btnback {
            background-color: #fff;
            color: #541313;
            border: solid 1px #541313;
        }
        .btnback:hover {
            background-color: #541313;
            color: #fff;
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
    <div class="logo"> 
        <img src="../../imgs/obraplanner7.png" alt="Logo do ObraPlanner">
    </div>
</header>
<nav class="sidebar">
    <ul>
        <li><a href="../dash.php"><i class="bx bx-home"></i> Home</a></li>
        <li><a href="../projetos/proj.php"><i class="bx bx-folder"></i> Projetos</a></li>
        <li><a href="../notificacaos/not.php"><i class="bx bx-bell"></i> Notificações</a></li>
        <li><a href="../presenca/presenca.php"><i class="bx bx-task"></i> Presença</a></li>
        <li><a href="config.php"><i class="bx bx-cog"></i> Configurações</a></li>
        <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
    </ul>
</nav>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
    <h1>Editar Informações</h1>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group" style="display: flex; justify-content: center; align-items: center; text-align: center;">
    <?php if (!empty($funcionario['foto'])): ?>
        <label class="custom-file-upload">
            <img id="imgPreview" src="../../uploads/fotos/<?php echo htmlspecialchars($funcionario['foto']); ?>" alt="" onclick="document.getElementById('foto-input').click();" style="width: 110px; height: 100px; object-fit: cover;">
            <input type="file" id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
        </label>
    <?php else: ?>
        <label class="custom-file-upload">
            <img id="imgPreview" src="../../imgs/do-utilizador (1).png" alt="Imagem prévia" style="width: 110px; height: 100px; object-fit: cover;">
            <input type="file" id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
        </label>
    <?php endif; ?>
</div>
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" class="form-control" required value="<?php echo htmlspecialchars($funcionario['nome']); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($funcionario['email']); ?>">
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" class="form-control" value="<?php echo htmlspecialchars($funcionario['telefone']); ?>">
            </div>
            <div class="form-group">
                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" class="form-control" required value="<?php echo htmlspecialchars($funcionario['cpf']); ?>">
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" class="form-control" placeholder="Deixe em branco para manter a atual">
            </div>
            <div class="button-container">
                <button type="submit" class="btnproj">Salvar</button>
                <a href="config.php" class="btnback">Cancelar</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
