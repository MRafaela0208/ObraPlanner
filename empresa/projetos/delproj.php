<?php
session_start();

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("ID do projeto não especificado.");
}

$projeto_id = $_GET['id'];

$conn = new mysqli('localhost', 'root', '', 'obra_planner');
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$conn->begin_transaction();

try {
    $sql_etapas = "DELETE FROM etapas WHERE projeto_id = ?";
    $stmt_etapas = $conn->prepare($sql_etapas);
    $stmt_etapas->bind_param("i", $projeto_id);
    $stmt_etapas->execute();

    $sql_func = "DELETE FROM projeto_funcionarios WHERE projeto_id = ?";
    $stmt_func = $conn->prepare($sql_func);
    $stmt_func->bind_param("i", $projeto_id);
    $stmt_func->execute();

    $sql_projeto = "DELETE FROM projetos WHERE projeto_id = ?";
    $stmt_projeto = $conn->prepare($sql_projeto);
    $stmt_projeto->bind_param("i", $projeto_id);
    $stmt_projeto->execute();

    $conn->commit();

    header("Location: proj.php");
        exit();
} catch (Exception $e) {
    $conn->rollback();
    echo "Erro ao deletar projeto: " . $e->getMessage();
}

$stmt_etapas->close();
$stmt_func->close();
$stmt_projeto->close();

$conn->close();
?>
