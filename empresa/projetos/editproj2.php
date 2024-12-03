<?php
session_start();

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$empresa_id = $_SESSION['UsuarioID']; 

$conn = new mysqli('localhost', 'root', '', 'obra_planner');
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$projeto_id = $_POST['projeto_id'];
$titulo = $_POST['titulo'];
$descricao = $_POST['descricao'];
$data_inicio = $_POST['data_inicio'];
$data_termino = $_POST['data_termino'];
$data_prev_ini = $_POST['data_prev_ini'];
$data_prev_ter = $_POST['data_prev_ter'];
$fiscal_id = $_POST['fiscal_id'];

$imagem = $_FILES['imagem'];
$documentacao = $_FILES['documentacao'];

$sql = "UPDATE projetos SET 
            titulo = ?, 
            descricao = ?, 
            data_inicio = ?, 
            data_termino = ?, 
            data_prev_ini = ?, 
            data_prev_ter = ?, 
            fiscal_id = ? 
        WHERE projeto_id = ? AND empresa_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssiisi", $titulo, $descricao, $data_inicio, $data_termino, $data_prev_ini, $data_prev_ter, $fiscal_id, $projeto_id, $empresa_id);

if ($stmt->execute()) {
    if ($imagem['size'] > 0) {
        $imgPath = 'uploads/' . basename($imagem['name']);
        if (move_uploaded_file($imagem['tmp_name'], $imgPath)) {
            $conn->query("UPDATE projetos SET imagem = '$imgPath' WHERE projeto_id = '$projeto_id'");
        } else {
            echo "Erro ao carregar a imagem.";
        }
    }

    if ($documentacao['size'] > 0) {
        $docPath = 'uploads/' . basename($documentacao['name']);
        if (move_uploaded_file($documentacao['tmp_name'], $docPath)) {
            $conn->query("UPDATE projetos SET documentacao = '$docPath' WHERE projeto_id = '$projeto_id'");
        } else {
            echo "Erro ao carregar a documentação.";
        }
    }

    echo "Projeto atualizado com sucesso!";
} else {
    echo "Erro ao atualizar o projeto: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: proj.php");
exit();
?>
