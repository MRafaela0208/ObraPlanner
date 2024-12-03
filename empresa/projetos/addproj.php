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

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>ObraPlanner</title>
    <style>
        h2 {
            color: #135545;
            text-align: center;
            margin-top: 5px;
            font-size: 1.8rem;
        }

        .form-container {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            padding: 40px;
            border-radius: 12px;
            margin: 20px auto;
            width: 90%;
            max-width: 750px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-weight: 600;
            color: #074470;
            display: block;
            margin-bottom: 5px;
        }

        form input[type="text"],
        form textarea,
        form input[type="date"],
        form select {
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 15px;
            width: 100%;
            margin-bottom: 15px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .file-upload {
            position: relative;
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 15px;
        }

        .file-upload input[type="file"] {
            opacity: 0;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload .upload-icon {
            display: inline-flex;
            align-items: center;
            padding: 12px;
            border-radius: 5px;
            color: #074470;
            font-weight: 500;
        }

        .file-upload .upload-icon i {
            margin-right: 5px;
        }

        .upload-container {
            display: flex;
            justify-content: space-between;
        }

        .date-container {
            display: flex;
            justify-content: space-between;
        }

        .date-container > div {
            flex: 1;
            margin-right: 10px; 
        }

        .date-container > div:last-child {
            margin-right: 0; 
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 25px;
        }

        .btnproj {
            background-color: #074470;
            color: #ffffff;
            border: solid 1px #074470;
            padding: 0.6rem 1.2rem;
            font-size: 1rem;
            border-radius: 5px;
            transition: background-color 0.2s, color 0.2s;
            text-decoration: none;
            text-align: center;
        }

        .btnproj:hover {
            background-color: #ffffff;
            color: #074470;
            border: solid 1px #074470;
        }
        h4{
            margin-top: 5px;
            font-size: 18px;
            color: #135545;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="../../imgs/obraplanner7.png" alt="Logo do ObraPlanner" style="max-height: 50px;">
        </div>
    </header>
    <nav class="sidebar">
        <ul>
            <li><a href="../dash.php"><i class="bx bx-home"></i> Home</a></li>
            <li><a href="proj.php"><i class="bx bx-folder"></i> Projetos</a></li>
            <li><a href="../fiscal/fis.php"><i class="bx bx-user"></i> Fiscais</a></li>
            <li><a href="../funcionarios/func.php"><i class="bx bx-user"></i> Funcionários</a></li>
            <li><a href="../notificacaos/not.php"><i class="bx bx-bell"></i> Notificações</a></li>
            <li><a href="../relemp/relemp.php"><i class="bx bx-task"></i> Relatórios</a></li>
            <li><a href="../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
            <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
        </ul>
    </nav>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
        <h2>Adicionar Projeto</h2>
        <div class="form-container">
            <form action="addproj2.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="empresa_id" value="<?php echo htmlspecialchars($empresa_id); ?>">

                <label>Título:</label>
                <input type="text" name="titulo" required>

                <label>Descrição:</label>
                <textarea name="descricao" required></textarea>

                <div class="upload-container">
                    <div class="file-upload">
                    <input type="file" name="imagem" accept="image/*">
                    <div class="upload-icon"><i class="bx bx-upload"></i> Imagem (Opcional)</div>
                </div>
                <div class="file-upload">
                    <input type="file" name="documentacao" accept=".pdf">
                    <div class="upload-icon"><i class="bx bx-upload"></i> Documentação (Opcional)</div>
                </div>
            </div>

                <div class="date-container">
                    <div>
                        <label>Data Prevista de Início:</label>
                        <input type="date" name="data_prev_ini" required style="width: 90%;">
                    </div>
                    <div>
                        <label>Data de Início:</label>
                        <input type="date" name="data_inicio" required style="width: 90%;">
                    </div>
                </div>

                <div class="date-container">
                    <div>
                        <label>Data Prevista de Término:</label>
                        <input type="date" name="data_prev_ter" required style="width: 90%;">
                    </div>
                    <div>
                        <label>Data de Término:</label>
                        <input type="date" name="data_termino" required style="width: 90%;">
                    </div>
                </div>

                <label>Fiscal Responsável:</label>
                <select name="fiscal_id" required>
                    <?php
                    if (isset($empresa_id)) {
                        $query = "SELECT fiscal_id, nome FROM fiscais WHERE empresa_id = '$empresa_id'";
                        $result = $conn->query($query);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['fiscal_id']}'>{$row['nome']}</option>";
                            }
                        }
                    }
                    ?>
                </select>

                <label>Funcionários Envolvidos:</label>
                <select name="func_id[]" multiple required>
                    <?php
                    $queryFuncionarios = "SELECT func_id, nome FROM funcionarios WHERE empresa_id = '$empresa_id'";
                    $resultFuncionarios = $conn->query($queryFuncionarios);
                    if ($resultFuncionarios && $resultFuncionarios->num_rows > 0) {
                        while ($row = $resultFuncionarios->fetch_assoc()) {
                            echo "<option value='{$row['func_id']}'>{$row['nome']}</option>";
                        }
                    }
                    ?>
                </select>
                <p style="font-size: 0.9rem; color: #666;">* Segure a tecla Ctrl (ou Command no Mac) para selecionar até 5 funcionários.</p>

                <label>Quantidade de Etapas:</label>
                <input type="number" name="qtn_eta" min="1" required style="padding: 12px; border: 1px solid #ced4da; border-radius: 5px;">

                <div id="etapas-container"></div>

                <div class="button-container">
                    <button type="submit" class="btnproj">Adicionar Projeto</button>
                </div>
            </form>
        </div>

        <script>
            const etapasContainer = document.getElementById('etapas-container');
            const quantidadeEtapasInput = document.querySelector('input[name="qtn_eta"]');

            quantidadeEtapasInput.addEventListener('change', () => {
                const quantidade = quantidadeEtapasInput.value;
                etapasContainer.innerHTML = ''; 

                for (let i = 0; i < quantidade; i++) {
                    etapasContainer.innerHTML += `
                        <div class="etapa">
                            <h4>Etapa ${i + 1}</h4>
                            <label>Título da Etapa:</label>
                            <input type="text" name="etapa_titulo[]" required>
                            <label>Descrição da Etapa:</label>
                            <textarea name="etapa_descricao[]" required></textarea>
                            <div class="date-container">
                                <div>
                                    <label>Data Prevista de Início:</label>
                                    <input type="date" name="etapa_data_previa_inicio[]" required style="width: 90%;">
                                </div>
                                <div>
                                    <label>Data de Início:</label>
                                    <input type="date" name="etapa_data_inicio[]" required style="width: 90%;">
                                </div>
                            </div>
                            <div class="date-container">
                                <div>
                                    <label>Data Prevista de Término:</label>
                                    <input type="date" name="etapa_data_previa_termino[]" required style="width: 90%;">
                                </div>
                                <div>
                                    <label>Data de Término:</label>
                                    <input type="date" name="etapa_data_termino[]" required style="width: 90%;">
                                </div>
                            </div>
                        </div>
                    `;
                }
            });

            const funcionariosSelect = document.querySelector('select[name="funcionarios[]"]');
            funcionariosSelect.addEventListener('change', function () {
                if (funcionariosSelect.selectedOptions.length > 5) {
                    alert("Você pode selecionar no máximo 5 funcionários.");
                    funcionariosSelect.options[funcionariosSelect.selectedIndex].selected = false;
                }
            });
        </script>
    </main>
</body>
</html>
