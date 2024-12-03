<?php
include_once '../../includes/db_connect.php';

session_start();
$empresa_id = $_SESSION['UsuarioID']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($nome) || empty($email) || empty($telefone) || empty($cpf) || empty($senha)) {
        die("Erro: Todos os campos são obrigatórios.");
    }

    $func_id = uniqid('func_', true); 

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt_check_cpf = $conn->prepare("SELECT COUNT(*) FROM funcionarios WHERE cpf = ?");
    $stmt_check_cpf->bind_param("s", $cpf);
    $stmt_check_cpf->execute();
    $stmt_check_cpf->bind_result($cpf_exists);
    $stmt_check_cpf->fetch();
    $stmt_check_cpf->close();

    if ($cpf_exists > 0) {
        die("Erro: O CPF já está cadastrado.");
    }

    try {
        $stmt_funcionario = $conn->prepare("INSERT INTO funcionarios (func_id, nome, email, telefone, cpf, senha, empresa_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_funcionario->bind_param("ssssssi", $func_id, $nome, $email, $telefone, $cpf, $senha_hash, $empresa_id);
        if (!$stmt_funcionario->execute()) {
            throw new Exception("Erro ao cadastrar funcionário: " . $stmt_funcionario->error);
        }
        $stmt_funcionario->close();

        $stmt_usuario = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'Funcionário')");
        $stmt_usuario->bind_param("sss", $nome, $email, $senha_hash);
        if (!$stmt_usuario->execute()) {
            throw new Exception("Erro ao cadastrar usuário: " . $stmt_usuario->error);
        }
        $stmt_usuario->close();

        $mensagem_notificacao = "Novo funcionário cadastrado: $nome.";
        $stmt_notificacao = $conn->prepare("INSERT INTO notificacoes (usu_id, tipo_usuario, tipo_notificacao, mensagem) VALUES (?, 'empresa', 'novo_usuario_funcionario', ?)");
        $stmt_notificacao->bind_param("is", $empresa_id, $mensagem_notificacao);
        if (!$stmt_notificacao->execute()) {
            throw new Exception("Erro ao criar notificação: " . $stmt_notificacao->error);
        }
        $stmt_notificacao->close();

        header("Location: func.php?message=Funcionário cadastrado com sucesso!");
        exit;
    } catch (Exception $e) {
        die("Erro ao cadastrar: " . $e->getMessage());
    }
}

$conn->close();
?>
