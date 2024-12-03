<?php 
include_once '../../includes/db_connect.php';

session_start();
$empresa_id = $_SESSION['UsuarioID']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); 

    $stmt = $conn->prepare("INSERT INTO fiscais (nome, email, telefone, cpf, senha, empresa_id) VALUES (?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sssssi", $nome, $email, $telefone, $cpf, $senha, $empresa_id);

        if ($stmt->execute()) {
            $stmt_usuario = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'Fiscal')");

            if ($stmt_usuario) {
                $stmt_usuario->bind_param("sss", $nome, $email, $senha);

                if ($stmt_usuario->execute()) {
                    $tipo_usuario = 'empresa';  
                    $tipo_notificacao = 'novo_usuario_fiscal';
                    $mensagem = "Um novo fiscal foi cadastrado: " . $nome;
                    $data_criacao = date('Y-m-d H:i:s');  

                    $stmt_notificacao = $conn->prepare("INSERT INTO notificacoes (usu_id, tipo_usuario, tipo_notificacao, mensagem, data_criacao) 
                                                        VALUES (?, ?, ?, ?, ?)");
                    if ($stmt_notificacao) {
                        $stmt_notificacao->bind_param("issss", $empresa_id, $tipo_usuario, $tipo_notificacao, $mensagem, $data_criacao);
                        if ($stmt_notificacao->execute()) {
                            header("Location: fis.php?message=Fiscal cadastrado com sucesso!");
                            exit;
                        } else {
                            echo "Erro ao cadastrar a notificação: " . $stmt_notificacao->error;
                        }
                    } else {
                        echo "Erro ao preparar a consulta de notificação: " . $conn->error;
                    }

                    $stmt_notificacao->close();
                } else {
                    echo "Erro ao cadastrar usuário: " . $stmt_usuario->error;
                }
            } else {
                echo "Erro ao preparar a consulta para usuários: " . $conn->error;
            }

            $stmt_usuario->close();
        } else {
            echo "Erro ao cadastrar fiscal: " . $stmt->error;
        }
    } else {
        echo "Erro ao preparar a consulta: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
