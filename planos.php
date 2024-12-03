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

      html, body {
          width: 100vw;
          overflow-x: hidden;
          margin: 0;
          scroll-behavior: smooth;
          font-family: 'Lexend', sans-serif;
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
      }

      .planos-section {
          padding: 3rem 1rem;
          text-align: center;
          background-color: #fff;
      }

      .planos-section h2 {
          font-size: 2.5rem;
          color: #00244b;
      }

      .intro-text {
          color: #555;
          margin-top: 0.5rem;
      }

      .planos-container {
          display: flex;
          justify-content: center; 
          gap: 2rem; 
          max-width: 1000px;
          margin: 2rem auto; }

      .plano-card {
          background: #fff;
          border: 1px solid #ddd;
          border-radius: 8px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          width: 500px; 
          padding: 25px;
          text-align: left;
          display: flex; 
          flex-direction: column; 
          justify-content: space-between; 
      }

      .plano-card h3 {
          font-size: 1.5rem;
          color: #01306E;
          margin-bottom: 1rem;
          font-weight: bold;
          text-align: center;
      }

      .plano-card .p {
          font-size: 0.9rem;
          color: #555;
          margin-bottom: 1rem;
          text-align: center;
      }

      .beneficios {
          list-style: none;
          margin-bottom: 1rem;
      }

      .beneficios li {
          display: flex;
          align-items: center;
          margin: 0.5rem 0;
          color: #333;
      }

      .beneficios li i {
          color: #074470;
          margin-right: 8px;
          font-size: 1.2em;
      }

      .preco {
          font-size: 1.5rem;
          color: #074470;
          font-weight: bold;
          text-align: center; 
      }

      .btn-contratar {
          background-color: #074470;
          color: #fff;
          border: solid 1px #074470;
          padding: 0.5rem 1rem;
          font-size: 1rem;
          border-radius: 4px;
          cursor: pointer;
          transition: background-color 0.3s;
          align-self: center; 
      }

      .btn-contratar:hover {
        background-color: #fff;
        color: #074470;
        border: solid 1px #074470;      
      }
    </style>
</head>
<body>
  
<header>
    <img src="../imgs/obraplanner7.png" alt="Logo do Sistema" onclick="window.location.href='../index.php'">
</header>

<section id="planos" class="planos-section">
    <h2>Escolha seu Plano</h2>
    <p class="intro-text">Escolha seu plano para começar seu gerenciamento.</p>

    <div class="planos-container">
        <div class="plano-card">
            <h3>Plano Basic</h3>
            <p class="p">Ideal para pequenas empresas de construção que precisam de funcionalidades essenciais para iniciar e gerenciar projetos.</p>
            <ul class="beneficios">
                <li><i class='bx bxs-check-circle'></i> Criação e gestão de até 3 projetos simultâneos</li>
                <li><i class='bx bxs-check-circle'></i> Acompanhamento de cronogramas e etapas do projeto</li>
                <li><i class='bx bxs-check-circle'></i> Controle de presença para até 15 funcionários</li>
                <li><i class='bx bxs-check-circle'></i> Cadastro de até 2 fiscais e 3 equipes</li>
                <li><i class='bx bxs-check-circle'></i> Relatórios simples de progresso e tarefas</li>
            </ul>
            <p class="preco">R$ 99,00/mês</p>
            <button class="btn-contratar" data-plano="basic">Escolher Plano</button>
        </div>

        <div class="plano-card">
            <h3>Plano Premium</h3>
            <p class="p">Perfeito para médias e grandes empresas de construção que precisam de ferramentas completas e suporte dedicado para projetos complexos.</p>
            <ul class="beneficios">
                <li><i class='bx bxs-check-circle'></i> Inclui todas as funcionalidades do Plano Básico</li>
                <li><i class='bx bxs-check-circle'></i> Gestão de até 10 projetos simultâneos</li>
                <li><i class='bx bxs-check-circle'></i> Controle de presença para até 50 funcionários</li>
                <li><i class='bx bxs-check-circle'></i> Cadastro de até 5 fiscais e 10 equipes</li>
                <li><i class='bx bxs-check-circle'></i> Comunicação avançada com integrações (e.g., CRM, Email)</li>
            </ul>
            <p class="preco">R$ 299,00/mês</p>
            <button class="btn-contratar" data-plano="premium">Escolher Plano</button>
        </div>
    </div>
</section>
<footer>
    <p>&copy; 2024 Obra Planner. Todos os direitos reservados.</p>
</footer>
<script>
    document.querySelectorAll('.btn-contratar').forEach(button => {
        button.addEventListener('click', () => {
            const plano = button.getAttribute('data-plano');
            window.location.href = `pagamento.php?plano=${plano}`;
        });
    });
</script>
</body>
</html>
