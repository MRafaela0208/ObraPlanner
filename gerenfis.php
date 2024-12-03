<?php
include('../../../includes/db_connect.php');

$result = mysqli_query($conn, "SELECT * FROM fiscais");
?>
<!DOCTYPE html>
<html>
<head>
<title>ObraPlanner</title>
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
     th{
            text-align: center;
        }
        h4{
            color: #135545;
        }
        h3{
            color: #135545;
        }
    .btn-custom {
            border-radius: 20px; 
            transition: background-color 0.3s ease; 
            color: white;
            font-weight: 600;
            border: none;
        }
        .btn-primary:hover{
            background-color: #07488d;
            color: white;
        }
        .btn-info{
            background-color: white;
            color: #17a2b8;
        }
        .btn-info:hover {
            background-color: #17a2b8; 
            color: white; 
        }
        .btn-danger {
            background-color: white;
            color:#dc3545; 
        }
        .btn-danger:hover {
            background-color: #dc3545;
            color:white; 
        }
        .btn-success{
            background-color: white;
            color: #28a745;
        }
        .btn-success:hover{
            background-color:#28a745; 
        }
        .btn-secondary:hover{
            background-color: #555;
        }
        .btn-groupp {
        display: flex;
    }

    .btn-groupp a {
        text-align: center;
    }
</style>
<body>
<header>
        <div class="logo">
            <img src="../../../imgs/obraplanner7.png" alt="Logo do ObraPlanner" style="max-height: 50px;">
        </div>
    </header>
    <nav class="sidebar">
        <ul>
            <li><a href="../../dash.php"><i class="bx bx-home"></i> Home</a></li>
            <li><a href="../geren.php"><i class="bx bx-task"></i> Gerenciar Usuários</a></li>
            <li><a href="../../gerenciarproj/proj.php"><i class="bx bx-task"></i> Gerenciar Projetos</a></li>
            <li><a href="../../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
            <li><a href="../../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
        </ul>
    </nav>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 30px;">
    <h2>Gerenciamento de Fiscais</h2>
    <h4 style="display: flex; justify-content: space-between; align-items: center;">
    Fiscais Cadastrados <div class="btn-group mb-3" role="group"><a href="../geren.php" class="btn btn-custom btn-primary">Voltar</a>
    <a href="relatorio.php" class="btn btn-custom btn-secondary">Gerar Relatório</a></div>
</h4>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>CPF</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td style=" text-align: center;"><?php echo $row['fiscal_id']; ?></td>
                <td><?php echo $row['nome']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td style=" text-align: center;"><?php echo $row['telefone']; ?></td>
                <td style=" text-align: center;"><?php echo $row['cpf']; ?></td>
                <td class="btn-groupp">
                    <div class="btn-groupp">
                        <a href="edit.php?edit=<?php echo $row['fiscal_id']; ?>" class="btn btn-success btn-sm btn-custom editBtn">Editar</a>
                        <a href="deletar.php?delete=<?php echo $row['fiscal_id']; ?>" class="btn btn-danger btn-sm btn-custom editBtn" onclick="return confirm('Tem certeza que deseja deletar este Funcionário?')">Deletar</a>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </table>
    </main>
</body>
</html>
