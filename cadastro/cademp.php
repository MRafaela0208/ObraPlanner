<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/jquery.inputmask.min.js"></script>
    <title>ObraPlanner</title>
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Lexend:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap');
      @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap');

html, body {
    width: 100vw;
    overflow-x: hidden;
    margin: 0;
    scroll-behavior: smooth;
}

header {
    background-color: #00244b;
    padding: 5px 0;
    text-align: center;
}

header img {
    max-height: 50px;
}

footer {
    background-color: #00244b;
    color: white;
    text-align: center;
    padding: 5px 0;
    margin-top: 20px;
}

footer p {
    margin-top: 15px;
    font-family: 'Lexend', sans-serif;
}

.container {
    max-width: 1000px;
    margin: 0 auto;
    background-color: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin-top: 30px;
    position: relative;
}

h1 {
    color: #135545;
    margin-bottom: 20px;
    font-family: 'Lexend', sans-serif;
}

.mensagem{
    margin-left: 120px;
    margin-right: 120px;
    text-align: center;
    font-family: 'Manrope', sans-serif;
    color: #1d8f72;
}

.form-group {
    margin-bottom: 10px;
    text-align: left;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: 'Lexend', sans-serif;
}

.form-group label {
    display: block;
    font-weight: bold;
    color: #07488d;
    flex: 1;
}

.form-group input {
    width: 55%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-top: 5px;
    margin-right: 240px;
}

.form-group input[type="file"] {
    display: none;
}

#imgPreview {
    width: 190px; 
    height: 190px;
    border-radius: 50%;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    position: absolute;
    top: 50%;
    right: 5%; 
    transform: translateY(-50%);
    margin-right: 10px;
    margin-top: 10px;
}

.labellogo {
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    position: absolute;
    top: 50%;
    right: 8%;
    transform: translateY(-50%);
    margin-top: 120px; 
}

.btn-container {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    background-color: #00244b;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 20%;
    text-align: center;
    font-weight: bold;
}

.btn.cancelar {
    background-color: #777;
    color: white;
}

.btn:hover {
    background-color: white;
    color: #00244b;
}

.btn.cancelar:hover {
    background-color: white;
    color: #777;
}

.logo-preview {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 2px solid #00244b;
    object-fit: cover;
}

@media (max-width: 768px) {
    .container {
        padding: 20px;
    }

    .btn-container {
        flex-direction: column;
        gap: 10px;
    }

    .btn {
        width: 100%;
        margin-bottom: 10px;
    }

    h1 {
        font-size: 24px;
    }

    .form-group {
        flex-direction: column;
        align-items: flex-start;
    }

    .form-group input {
        margin-top: 5px;
        width: 100%;
    }

    .labellogo {
        position: relative;
        margin-top: 20px;
    }

    #imgPreview {
        width: 60px;
        height: 60px;
        right: 15px;
    }
}

@media (max-width: 480px) {
    header img {
        max-height: 40px;
    }

    .container {
        padding: 10px;
    }

    .form-group input {
        padding: 8px;
    }
}

    </style>
</head>
<body>
    <header>
        <img src="../imgs/obraplanner7.png" alt="Logo do Sistema">
    </header>

    <div class="container">
        <h1>Bem-vindo ao ObraPlanner</h1>
        <p class="mensagem">
            O ObraPlanner é a plataforma ideal para a gestão de sua obra. Cadastre sua <strong >Empresa</strong> e tenha total controle sobre seus projetos.
        </p>
        <form action="processo_cadastro_empresa.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="nome">Nome da Empresa</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" required inputmode="numeric" maxlength="15" oninput="formatarTelefone(this)" onkeydown="permitirSomenteNumeros(event)">
            </div>

            <div class="form-group">
                <label for="endereco">Endereço</label>
                <input type="text" id="endereco" name="endereco" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            </div>

            <div class="form-group">
                <label for="cnpj">CNPJ</label>
                <input type="text" id="cnpj" name="cnpj" required inputmode="numeric" maxlength="18" oninput="formatarCNPJ(this)" onkeydown="permitirSomenteNumeros(event)">
            </div>

            <div class="form-group">
                <label for="responsavel">Responsável</label>
                <input type="text" id="responsavel" name="responsavel" required>
            </div>

            <div class="form-group">
                <label class="custom-file-upload">
                    <img id="imgPreview" src="../imgs/do-utilizador (1).png" alt="Imagem prévia">
                    <input type="file" id="logo" name="logo" accept="image/*" onchange="previewImage(event)">
                </label>
                <label for="logo" class="labellogo">Logo da Empresa</label>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn">Continuar</button>
                <button type="button" class="btn cancelar" onclick="window.location.href='../index.php'">Cancelar</button>
            </div>
        </form>
    </div>
<footer>
    <p>&copy; 2024 Obra Planner. Todos os direitos reservados.</p>
</footer>

<script>
    function validateForm() {
        const senha = document.getElementById("senha").value;
        const confirmarSenha = document.getElementById("confirmar_senha").value;

        if (senha !== confirmarSenha) {
            alert("As senhas não coincidem. Por favor, tente novamente.");
            return false; 
        }
        
    }

    function previewImage(event) {
        const reader = new FileReader();
        const imageField = document.getElementById("imgPreview");

        reader.onload = function() {
            if (reader.readyState === 2) {
                imageField.src = reader.result;
            }
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    function permitirSomenteNumeros(event) {
        const key = event.key;
        if (!/[0-9]/.test(key) && event.keyCode !== 8 && event.keyCode !== 37 && event.keyCode !== 39) {
            event.preventDefault();
        }
    }

    function formatarTelefone(telefone) {
        telefone.value = telefone.value
            .replace(/\D/g, "")
            .replace(/(\d{2})(\d)/, "($1) $2")
            .replace(/(\d{5})(\d)/, "$1-$2")
            .slice(0, 15); 
    }

    function formatarCNPJ(cnpj) {
        cnpj.value = cnpj.value
            .replace(/\D/g, "")
            .replace(/(\d{2})(\d)/, "$1.$2")
            .replace(/(\d{3})(\d)/, "$1.$2")
            .replace(/(\d{3})(\d)/, "$1/$2")
            .replace(/(\d{4})(\d)/, "$1-$2")
            .slice(0, 18); 
    }
</script>

</body>
</html>
