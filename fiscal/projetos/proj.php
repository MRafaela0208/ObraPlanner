<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../includes/db_connect.php';
session_start(); 
if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$fiscal_id = $_SESSION['UsuarioID'];

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT p.titulo AS projeto_titulo, p.descricao, p.projeto_id, 
                 GROUP_CONCAT(e.titulo SEPARATOR ', ') AS etapas_titulos
          FROM projetos p
          LEFT JOIN etapas e ON p.projeto_id = e.projeto_id
          WHERE p.fiscal_id = ? AND (p.titulo LIKE ? OR p.descricao LIKE ?)
          GROUP BY p.projeto_id";

$stmt = $conn->prepare($query);
$searchTerm = "%" . $search . "%"; 
$stmt->bind_param("iss", $fiscal_id, $searchTerm, $searchTerm);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>ObraPlanner</title>
</head>
<style>
    .projects-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .project-card {
        margin-top: 10px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: calc(33.33% - 20px);
    }
    .project-card p strong {
        color: #135545;
    }
    .project-card h3 {
        margin: 0 0 10px;
        color: #274a70;
    }
    .btn-custom {
        background-color: white;
        color: #074470;
        border: solid 1px #074470;
        padding: 10px 20px;
        font-size: 1em;
        border-radius: 10px;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }
    .btn-custom:hover {
        background-color: #074470;
        color: white;
    }
    .no-projects, .info-message {
        display: flex;
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 20px; 
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 30px;
        border-radius: 10px;
    }
    .no-projects .text-content, .info-message .text-content {
        flex: 1; 
        margin-right: 20px; 
        color: #000;
        font-weight: 500;
    }
    .no-projects h2, .info-message h2 {
        color: #135545;
        font-weight: 700;
    }
    .no-projects img, .info-message img {
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
    <?php if ($result->num_rows === 0): ?>
        <div class="no-projects">
            <div class="text-content">
                <h2>Bem-vindo à Central de Projetos!</h2>
                <p>Você ainda não possui projetos sob sua supervisão. Nesta seção, serão exibidos os projetos criados pela empresa que você fiscaliza, juntamente com detalhes de cada etapa. Assim que algum projeto for designado a você, ele aparecerá automaticamente nesta área.</p>
            </div>
            <img src="../imgs/projetos5.png" alt="Imagem ilustrativa de projetos">
        </div>
    <?php else: ?>
        <form action="proj.php" method="GET" class="form-inline mb-3 d-flex justify-content-center">
    <div class="input-group" style="max-width: 400px;">
        <input type="text" name="search" class="form-control" placeholder="Buscar projetos..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" class="btn btn-primary">
            <i class="bx bx-search"></i>  
        </button>
    </div>
</form>
        <div class="info-message">
            <div class="text-content">
                <h2>Central de Projetos</h2>
                <p>Aqui você encontrará todos os projetos sob sua supervisão. Cada projeto é acompanhado de suas etapas, permitindo que você monitore o progresso.</p>
            </div>
            <img src="../imgs/projetos5.png" alt="Imagem ilustrativa de projetos">
        </div>
        <div class="projects-container">
            <?php while ($projeto = mysqli_fetch_assoc($result)): ?>
                <div class="project-card">
                    <h3><?php echo htmlspecialchars($projeto['projeto_titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($projeto['descricao']); ?></p>
                    <p><strong>Etapas:</strong> <?php echo htmlspecialchars($projeto['etapas_titulos']); ?></p>
                    <a href="proj2.php?projeto_id=<?php echo $projeto['projeto_id']; ?>" class="btn-custom">Ver Detalhes</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</main>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
