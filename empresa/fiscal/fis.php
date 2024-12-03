<?php
session_start(); 

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$empresa_id = $_SESSION['UsuarioID'];

$conn = mysqli_connect('localhost', 'root', '', 'obra_planner');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$sql = "SELECT * FROM fiscais WHERE empresa_id = '$empresa_id'";

if ($search) {
    $sql .= " AND (nome LIKE '%$search%' OR email LIKE '%$search%' OR telefone LIKE '%$search%' OR cpf LIKE '%$search%')";
}

$result = $conn->query($sql);
if (!$result) {
    echo "Erro na consulta: " . mysqli_error($conn);
    exit();
}

$conn->close();
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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Lexend:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap');
        h1 {
            color: #135545;
            text-align: center;
        }
        .fiscal-card {
            display: flex;
            flex-direction: column;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin: 10px 0;
        }
        .fiscal-info {
            font-size: 16px;
            color: #135545;
            font-weight: 500;
        }
        .fiscal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 10px;
        }
        .btnproj {
            padding: 0.1rem 0.5rem;
            font-size: 1rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 10px;
            font-weight: 500;
        }
        .fiscal-details {
            display: none;
            font-size: 14px;
            color: #333;
            margin-top: 10px;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
        }
        .fiscal-details strong{
            color: #324363;
        }
        .btncriar {
            background-color: #fff; 
            color: #074470; 
            padding: 10px 15px; 
            border: solid 1px #074470; 
            border-radius: 5px; 
            cursor: pointer; 
        }
        .btncriar:hover {
            background-color: #074470;
            color: #fff;
            border: solid 1px #074470; 
        }
        .welcome-message {
            display: flex;
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 20px; 
            background-color:#fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 10px;
        }
        .welcome-text {
            flex: 1; 
            margin-right: 20px; 
            color: #000;
            font-weight: 500;
        }
        .welcome-text h2{
            color:#135545;
            font-weight: 700;
        }
        .welcome-image img {
            max-width: 100%; 
            height: auto;
        }
        .form-inline .input-group button {
            background-color: #074470;
            border: none;
            padding: 8px;
            color: white;
            font-size: 18px;
            border-radius: 5px;
            
        }
        .form-inline .input-group input {
            border-radius: 5px;
            padding: 8px;
            border: 1px solid #ccc;
        }
        .search-form {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;  
            width: 100%; 
            max-width: 600px; 
            margin-left: auto; 
            margin-right: auto; 
        }
        
    </style>
    <script>
        function toggleDetails(fiscalId) {
            var detailsDiv = document.getElementById("details-" + fiscalId);
            if (detailsDiv.style.display === "none" || detailsDiv.style.display === "") {
                detailsDiv.style.display = "block";
            } else {
                detailsDiv.style.display = "none";
            }
        }
    </script>
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
            <li><a href="fis.php"><i class="bx bx-user"></i> Fiscais</a></li>
            <li><a href="../funcionarios/func.php"><i class="bx bx-user"></i> Funcionários</a></li>
            <li><a href="../notificacaos/not.php"><i class="bx bx-bell"></i> Notificações</a></li>
            <li><a href="../relemp/relemp.php"><i class="bx bx-task"></i> Relatórios</a></li>
            <li><a href="../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
            <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
        </ul>
    </nav>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
    <form action="fis.php" method="GET" class="form-inline search-form">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar fiscais..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="max-width: 400px;">
                <button type="submit" class="btn">
                    <i class="bx bx-search"></i>
                </button>
            </div>
        </form>
    <div class="welcome-message">
    <div class="welcome-text">
        <h2>Bem-vindo à Central de Fiscais!</h2>
        <p>Aqui você pode visualizar e gerenciar todos os fiscais cadastrados na sua empresa. Utilize o botão abaixo para adicionar novos fiscais, além de editar ou excluir os existentes conforme necessário.</p>
        <button class="btncriar" onclick="window.location.href='cadfis.php'"><i class="bx bx-plus-circle"></i> Cadastrar Novo Fiscal</button>
        <button class="btncriar" onclick="window.location.href='relatorio.php'"><i class="bx bx-download"></i> Gerar Relatório</button>
    </div>
    <div class="welcome-image">
        <img src="../imgs/fiscal.png" alt="Imagem de boas-vindas" style="max-width: 300px;">
    </div>
</div>

<?php if ($result->num_rows > 0): ?>
    <section class="fiscais-overview">
        <div class="lista">
            <?php while ($fiscal = $result->fetch_assoc()): ?>
                <div class="fiscal-card">
                    <div class="fiscal-info">
                        <strong><?php echo htmlspecialchars($fiscal['nome']); ?></strong>
                    </div>
                    <div class="fiscal-buttons">
                        <button onclick="toggleDetails(<?php echo $fiscal['fiscal_id']; ?>)" class="btnproj" style="color: #4CAF50; background-color:#fff; border:none;">
                            <i class="bx bx-show"></i>
                        </button>
                        <a href="editfis.php?id=<?php echo $fiscal['fiscal_id']; ?>" class="btnproj" style="color: #2196F3;"> 
                        <i class='bx bx-pencil'></i> Editar
                        </a>
                        <a href="delfis.php?id=<?php echo $fiscal['fiscal_id']; ?>" class="btnproj" style="color: #F44336;"> 
                        <i class='bx bx-trash'></i> Excluir
                        </a>
                    </div>
                    <div id="details-<?php echo $fiscal['fiscal_id']; ?>" class="fiscal-details">
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($fiscal['email']); ?></p>
                        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($fiscal['telefone']); ?></p>
                        <p><strong>CPF:</strong> <?php echo htmlspecialchars($fiscal['cpf']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
<?php else: ?>
    <p  style="text-align: center;">Atualmente, não há fiscais cadastrados. Clique em "Cadastrar Novo Fiscal" para iniciar.</p>
<?php endif; ?>
    </main>
</body>
</html>
