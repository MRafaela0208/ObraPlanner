<?php
session_start();
if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$fiscal_id = $_GET['id'];

// Conectar ao banco de dados
$conn = mysqli_connect('localhost', 'root', '', 'obra_planner');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 1. Excluir ou atualizar os projetos associados
// $sqlDeleteProjetos = "DELETE FROM projetos WHERE fiscal_id = ?"; // Para excluir projetos
$sqlUpdateProjetos = "UPDATE projetos SET fiscal_id = NULL WHERE fiscal_id = ?"; // Para atualizar os projetos
$stmtUpdate = $conn->prepare($sqlUpdateProjetos);
$stmtUpdate->bind_param("i", $fiscal_id);
$stmtUpdate->execute();

// 2. Excluir o fiscal
$sql = "DELETE FROM fiscais WHERE fiscal_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $fiscal_id);
$stmt->execute();

// Redirecionar após a exclusão
header("Location: fis.php");
exit();
?>
