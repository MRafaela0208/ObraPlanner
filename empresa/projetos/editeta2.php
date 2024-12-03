<?php 
include '../../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $etapa_id = intval($_POST['etapa_id']);
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $observacoes = $_POST['observacoes'];
    $data_inicio = $_POST['data_inicio'];
    $data_termino = $_POST['data_termino'];
    $data_previa_inicio = $_POST['data_previa_inicio'];
    $data_previa_termino = $_POST['data_previa_termino'];

    $sql = "UPDATE etapas SET titulo = ?, descricao = ?, observacoes = ?, data_inicio = ?, data_termino = ?, data_previa_inicio = ?, data_previa_termino = ? WHERE etapa_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $titulo, $descricao, $observacoes, $data_inicio, $data_termino, $data_previa_inicio, $data_previa_termino, $etapa_id);

    if ($stmt->execute()) {
        header("Location: proj.php?id=" . $etapa['projeto_id'] . "&message=Etapa atualizada com sucesso!");
        exit();
    } else {
        header("Location: proj.php?id=" . $etapa['projeto_id'] . "&message=Erro ao atualizar a etapa.");
        exit();
    }
} else {
    header("Location: proj.php?id=" . $etapa['projeto_id'] . "&message=Erro ao processar a solicitação.");
    exit();
}
?>
