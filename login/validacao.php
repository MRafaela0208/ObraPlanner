<?php
session_start();

$con = mysqli_connect('localhost', 'root', '', 'obra_planner') or trigger_error(mysqli_error($con));

$email = mysqli_real_escape_string($con, $_POST['email']);
$senha = mysqli_real_escape_string($con, $_POST['senha']);

$tipoUsuario = '';
$resultado = null;

// Função para verificar o usuário e senha
function verificar_usuario($con, $sql, $tipo) {
    $query = mysqli_query($con, $sql);
    if (!$query) {
        $_SESSION['login_erro'] = "Erro na consulta SQL.";
        header("Location: login.php");
        exit;
    }
    if (mysqli_num_rows($query) == 1) {
        return mysqli_fetch_assoc($query);
    }
    return null;
}

// Consultar a tabela 'empresas'
$sql_empresa = "SELECT empresa_id AS id, nome, senha, 'empresa' AS tipo FROM empresas WHERE email = '$email' LIMIT 1";
$resultado = verificar_usuario($con, $sql_empresa, 'empresa');

if ($resultado && password_verify($senha, $resultado['senha'])) {
    $tipoUsuario = 'empresa';
} else {
    // Consultar a tabela 'fiscais'
    $sql_fiscal = "SELECT fiscal_id AS id, nome, senha, 'fiscal' AS tipo FROM fiscais WHERE email = '$email' LIMIT 1";
    $resultado = verificar_usuario($con, $sql_fiscal, 'fiscal');

    if ($resultado && password_verify($senha, $resultado['senha'])) {
        $tipoUsuario = 'fiscal';
    } else {
        // Consultar a tabela 'funcionarios'
        $sql_funcionario = "SELECT func_id AS id, nome, senha, 'funcionario' AS tipo FROM funcionarios WHERE email = '$email' LIMIT 1";
        $resultado = verificar_usuario($con, $sql_funcionario, 'funcionario');

        if ($resultado && password_verify($senha, $resultado['senha'])) {
            $tipoUsuario = 'funcionario';
        } else {
            // Consultar a tabela 'admin'
            $sql_admin = "SELECT admin_id AS id, nome, senha, 'admin' AS tipo FROM admin WHERE email = '$email' LIMIT 1";
            $resultado = verificar_usuario($con, $sql_admin, 'admin');

            if ($resultado && password_verify($senha, $resultado['senha'])) {
                $tipoUsuario = 'admin';
            }
        }
    }
}

if ($resultado) {
    $_SESSION['UsuarioID'] = $resultado['id'];
    $_SESSION['UsuarioNome'] = $resultado['nome'];
    $_SESSION['UsuarioTipo'] = $tipoUsuario;

    switch ($tipoUsuario) {
        case 'empresa':
            header("Location: ../empresa/dash.php");
            break;
        case 'fiscal':
            header("Location: ../fiscal/dash.php");
            break;
        case 'funcionario':
            header("Location: ../funcionario/dash.php");
            break;
        case 'admin':
            header("Location: ../admin/dash.php");
            break;
        default:
            $_SESSION['login_erro'] = "Email ou senha incorretos. Por favor, insira novamente.";
            header("Location: login.php");
            exit;
    }
    exit();
} else {
    $_SESSION['login_erro'] = "Email ou senha incorretos. Por favor, insira novamente.";
    header("Location: login.php");
    exit;
}
?>
