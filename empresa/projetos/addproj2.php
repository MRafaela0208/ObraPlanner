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

$conn->set_charset("utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction();

    try {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $data_prev_ini = $_POST['data_prev_ini'];
        $data_inicio = $_POST['data_inicio'];
        $data_prev_ter = $_POST['data_prev_ter'];
        $data_termino = $_POST['data_termino'];
        $fiscal_id = $_POST['fiscal_id'];
        $funcionarios = $_POST['func_id']; 
        $qtn_eta = $_POST['qtn_eta'];

        $imagem = null;
        $documentacao = null;

        if (!empty($_FILES['imagem']['name'])) {
            $imagem = 'uploads/' . basename($_FILES['imagem']['name']);
            move_uploaded_file($_FILES['imagem']['tmp_name'], $imagem);
        }

        if (!empty($_FILES['documentacao']['name'])) {
            $documentacao = 'uploads/' . basename($_FILES['documentacao']['name']);
            move_uploaded_file($_FILES['documentacao']['tmp_name'], $documentacao);
        }

        $sql = "INSERT INTO projetos (titulo, descricao, imagem, documentacao, data_inicio, data_termino, data_prev_ini, data_prev_ter, empresa_id, fiscal_id, qtn_eta) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssisi",
            $titulo, $descricao, $imagem, $documentacao, $data_inicio, $data_termino, $data_prev_ini, $data_prev_ter,
            $empresa_id, $fiscal_id, $qtn_eta
        );
        $stmt->execute();

        $projeto_id = $stmt->insert_id;

        $sql_func = "INSERT INTO projeto_funcionarios (projeto_id, func_id) VALUES (?, ?)";
        $stmt_func = $conn->prepare($sql_func);

        foreach ($funcionarios as $func_id) {
            $stmt_func->bind_param("is", $projeto_id, $func_id);
            $stmt_func->execute();
        }

        $etapa_titulos = $_POST['etapa_titulo'];
        $etapa_descricoes = $_POST['etapa_descricao'];
        $etapa_data_previa_inicio = $_POST['etapa_data_previa_inicio'];
        $etapa_data_inicio = $_POST['etapa_data_inicio'];
        $etapa_data_previa_termino = $_POST['etapa_data_previa_termino'];
        $etapa_data_termino = $_POST['etapa_data_termino'];

        $sql_etapa = "INSERT INTO etapas (projeto_id, titulo, descricao, data_previa_inicio, data_inicio, data_previa_termino, data_termino) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_etapa = $conn->prepare($sql_etapa);

        for ($i = 0; $i < count($etapa_titulos); $i++) {
            $stmt_etapa->bind_param(
                "issssss",
                $projeto_id,
                $etapa_titulos[$i],
                $etapa_descricoes[$i],
                $etapa_data_previa_inicio[$i],
                $etapa_data_inicio[$i],
                $etapa_data_previa_termino[$i],
                $etapa_data_termino[$i]
            );
            $stmt_etapa->execute();
        }

        $tipo_notificacao = 'novo_projeto';
        $mensagem = "Novo projeto criado: $titulo.";
        $sql_notificacao = "INSERT INTO notificacoes (usu_id, tipo_usuario, tipo_notificacao, mensagem) 
                            VALUES (?, 'empresa', ?, ?)";
        $stmt_notificacao = $conn->prepare($sql_notificacao);
        $stmt_notificacao->bind_param("iss", $empresa_id, $tipo_notificacao, $mensagem);
        $stmt_notificacao->execute();

        $conn->commit();

        header("Location: proj.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao adicionar projeto: " . $e->getMessage();
    }

    $stmt->close();
    $stmt_func->close();
    $stmt_etapa->close();
    $stmt_notificacao->close();
}

$conn->close();
?>
