<?php 
include '../../includes/db_connect.php';

if (isset($_GET['id'])) {
    $etapa_id = intval($_GET['id']); 

    $sql = "DELETE FROM etapas WHERE etapa_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $etapa_id);

    if ($stmt->execute()) {
        header("Location: proj.php?id={$etapa_id}&message=Etapa deletada com sucesso!");
        exit();
    } else {
        header("Location: proj.php?id={$etapa_id}&message=Erro ao deletar a etapa.");
        exit();
    }
} else {
    header("Location: proj.php?message=ID da etapa não especificado.");
    exit();
}
?>
