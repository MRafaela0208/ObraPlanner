<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['projeto_id'], $_POST['progresso'], $_POST['status'], $_POST['etapas'])) {
        $projeto_id = intval($_POST['projeto_id']);
        $progresso = floatval($_POST['progresso']);
        $status = $_POST['status'];
        $etapas_concluidas = $_POST['etapas']; 

        if (!in_array($status, ['Concluído', 'Em andamento', 'Atrasado'])) {
            echo "Status inválido. Valor atual: $status";
            exit();
        }

        $query = "UPDATE projetos SET progresso = ?, status = ? WHERE projeto_id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            echo "Erro na preparação da consulta: " . $conn->error;
            exit();
        }

        $stmt->bind_param("dsi", $progresso, $status, $projeto_id);

        if ($stmt->execute()) {
            if (!empty($etapas_concluidas)) {
                foreach ($etapas_concluidas as $etapa_id) {
                    $query_etapa = "UPDATE etapas SET concluida = 1 WHERE etapa_id = ? AND projeto_id = ?";
                    $stmt_etapa = $conn->prepare($query_etapa);
                    $stmt_etapa->bind_param("ii", $etapa_id, $projeto_id);
                    $stmt_etapa->execute();
                    $stmt_etapa->close();
                }
            }

            header("Location: proj.php?projeto_id=" . $projeto_id);
            exit();
        } else {
            echo "Erro ao atualizar o progresso e status: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Erro: Campos obrigatórios ausentes.";
        if (!isset($_POST['projeto_id'])) echo " 'projeto_id' ausente.";
        if (!isset($_POST['progresso'])) echo " 'progresso' ausente.";
        if (!isset($_POST['status'])) echo " 'status' ausente.";
        if (!isset($_POST['etapas'])) echo " 'etapas' ausente.";
    }
}

$conn->close();

if ($status_atual !== 'Concluído') { ?>
    <form action="proj3.php" method="POST">
        <input type="hidden" name="projeto_id" value="<?php echo $projeto_id; ?>">
        <input type="hidden" id="input-progresso" name="progresso">
        <input type="hidden" id="input-status" name="status">

        <?php while ($etapa = $etapas->fetch_assoc()) { ?>
            <div>
                <?php if ($etapa['concluida']) { ?>
                    <i class="bx bx-check-circle" style="color: green;"></i> 
                    <label><?php echo htmlspecialchars($etapa['titulo']); ?></label>
                <?php } else { ?>
                    <input type="checkbox" class="etapa-checkbox" name="etapas[]" value="<?php echo $etapa['etapa_id']; ?>" onclick="calcularProgresso()">
                    <label for="etapa_<?php echo $etapa['etapa_id']; ?>"><?php echo htmlspecialchars($etapa['titulo']); ?></label>
                <?php } ?>
            </div>
        <?php } ?>

        <button type="submit">Atualizar Progresso</button>
    </form>
<?php } else { ?>
    <p>Todas as Etapas Foram Concluídas.</p>
<?php } ?>
