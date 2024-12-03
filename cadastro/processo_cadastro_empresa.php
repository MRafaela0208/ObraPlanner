<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telefone = mysqli_real_escape_string($conn, $_POST['telefone']);
    $endereco = mysqli_real_escape_string($conn, $_POST['endereco']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);
    $confirmar_senha = mysqli_real_escape_string($conn, $_POST['confirmar_senha']);
    $cnpj = mysqli_real_escape_string($conn, $_POST['cnpj']);
    $responsavel = mysqli_real_escape_string($conn, $_POST['responsavel']);
    $logo = $_FILES['logo']['name'];

    if ($senha !== $confirmar_senha) {
        echo "As senhas não coincidem.";
        exit();
    }

    $hashed_password = password_hash($senha, PASSWORD_DEFAULT);

    $target_dir = "../uploads/logos/";
    $target_file = $target_dir . basename($logo);

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
        $check = getimagesize($_FILES['logo']['tmp_name']);
        if ($check === false) {
            echo "O arquivo enviado não é uma imagem.";
            exit();
        }
    }

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'avif'];
    $file_extension = pathinfo($logo, PATHINFO_EXTENSION);
    if (!in_array($file_extension, $allowed_extensions)) {
        echo "Extensão de arquivo não permitida.";
        exit();
    }

    if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
        $sql_check_cnpj = "SELECT * FROM empresas WHERE cnpj = '$cnpj'";
        $result = mysqli_query($conn, $sql_check_cnpj);

        if (mysqli_num_rows($result) > 0) {
            echo "CNPJ já cadastrado.";
            exit();
        }

        $sql_insert_empresa = "INSERT INTO empresas (nome, email, telefone, endereco, cnpj, senha, responsavel, logo) 
                                VALUES ('$nome', '$email', '$telefone', '$endereco', '$cnpj', '$hashed_password', '$responsavel', '$logo')";

        if (mysqli_query($conn, $sql_insert_empresa)) {
            $sql_insert_usuario = "INSERT INTO usuarios (nome, email, senha, tipo) 
                                   VALUES ('$nome', '$email', '$hashed_password', 'Empresa')";

            if (mysqli_query($conn, $sql_insert_usuario)) {
                header("Location: ../login/login.php");
                exit();
            } else {
                echo "Erro ao inserir usuário: " . mysqli_error($conn);
            }
        } else {
            echo "Erro ao inserir empresa: " . mysqli_error($conn);
        }
    } else {
        echo "Desculpe, houve um erro ao enviar o arquivo.";
    }

    mysqli_close($conn);
}
?>
