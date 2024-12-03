<?php
session_start();
include_once '../../includes/db_connect.php';

if (!isset($_SESSION['UsuarioID']) || !isset($_GET['id']) || !isset($_GET['redirect'])) {
    header("Location: not.php");
    exit();
}

$empresa_id = $_SESSION['UsuarioID'];
$notificacao_id = $_GET['id'];
$redirect = $_GET['redirect'];

$conn = new mysqli('localhost', 'root', '', 'obra_planner');
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$stmt = $conn->prepare("UPDATE notificacoes SET visualizada = 1 WHERE notificacao_id = ? AND usu_id = ?");
$stmt->bind_param("ii", $notificacao_id, $empresa_id);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: $redirect");
exit();
?>
