<?php
include '../../includes/db_connect.php';

if (isset($_GET['id'])) {
    $func_id = $_GET['id'];

    $sql = "DELETE FROM funcionarios WHERE func_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $func_id);

    if ($stmt->execute()) {
        echo "Funcionário deletado com sucesso!";
    } else {
        echo "Erro ao deletar funcionário: " . $stmt->error;
    }

    header("Location: func.php");
    exit;
} else {
    echo "ID do funcionário não fornecido.";
}
$conn->close();
?>
