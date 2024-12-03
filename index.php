<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style.css">
    <style>
              @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap');
              @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Lexend:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap');
              body{
                font-family:'Lexend', sans-serif;
              }
              .action-button {
    background-color: #ffe600;
    color: black;
    border: none;
    padding: 15px 30px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 1rem;
    font-weight: bold;
    transition: background-color 0.3s ease;
    text-decoration: none;
}

.action-button:hover {
    background-color: black;
    color: #ffe600;
}

    </style>
    <title>ObraPlanner</title>
</head>
<body>
<header>
    <div class="logo">
        <img src="imgs/obraplanner1.png" alt="Logo Obra Planner">
    </div>
    <div class="header-buttons">
        <a href="planos/planos.php" class="login-button">Planos</a>
        <div class="line-separator"></div>
        <a href="login/login.php" class="login-button">Entrar</a>
        <div class="line-separator"></div>
        <a href="cadastro/cademp.php" class="login-button">Cadastrar-se</a>
    </div>
</header>

<section class="banner">
    <div class="banner-content">
        <h1>Transforme a <strong style="font-style: italic;">Gestão de Obras</strong> com a <strong style="font-style: italic;">Obra Planner</strong></h1>
        <p>A plataforma completa para gerenciar projetos de construção de forma eficiente e integrada.</p>
        <a href="login/login.php" class="action-button">Experimentar Agora!</a>
    </div>
</section>

<section class="article">
    <div class="article-image">
        <img src="imgs/sobrenos.png" alt="Imagem do Artigo">
    </div>
    <div class="article-content">
        <h2>Sobre Nós</h2>
        <p>O <strong>Obra Planner</strong> é uma empresa inovadora dedicada a transformar a gestão de obras na indústria da construção civil. Fundada por especialistas com vasta experiência no setor, nossa missão é <strong>simplificar</strong> e <strong>otimizar</strong> cada aspecto do processo de construção, desde a concepção inicial até a entrega final.</p>
    </div>
</section>

<hr class="separador">

<section id="planos" class="planos-section">
  <h2>Planos</h2>
  <p>Encontre o plano ideal para sua empresa e garanta ferramentas completas de gerenciamento de obras e equipes.</p>
  <div class="planos-container">
    
    <div class="plano-card">
      <h3>Plano Basic</h3>
      <p class="descricao">Ideal para pequenas empresas de construção que precisam de funcionalidades essenciais para iniciar e gerenciar projetos.</p>
      <ul class="beneficios">
        <li>Criação e gestão de até 3 projetos simultâneos</li>
        <li>Acompanhamento de cronogramas e etapas do projeto</li>
        <li>Controle de presença para até 15 funcionários</li>
        <li>Cadastro de até 2 fiscais e 3 equipes</li>
        <li>Relatórios simples de progresso e tarefas</li>
      </ul>
      <p class="preco" style="color:#132154;">R$ 99,00/mês</p>
      <a href="cadastro/cademp.php" class="btn-contratar" style="text-decoration: none;">Contratar</a>
    </div>
    
    <div class="plano-card">
      <h3>Plano Premium</h3>
      <p class="descricao">Perfeito para médias e grandes empresas de construção que precisam de ferramentas completas e suporte dedicado para projetos complexos.</p>
      <ul class="beneficios">
        <li>Inclui todas as funcionalidades do Plano Básico</li>
        <li>Gestão de até 10 projetos simultâneos</li>
        <li>Controle de presença para até 50 funcionários</li>
        <li>Cadastro de até 5 fiscais e 10 equipes</li>
        <li>Relatórios avançados e personalizados</li>
      </ul>
      <p class="preco" style="color:#132154;">R$ 299,00/mês</p>
      <a href="cadastro/cademp.php" class="btn-contratar" style="text-decoration: none;">Contratar</a>
    </div>
    
  </div>
</section>

<hr class="separador">

<section class="benefits">
    <div class="benefits-content">
        <h2>Benefícios</h2>
        <div class="benefit">
            <i class='bx bx-line-chart'></i>
            <p><strong>Melhoria da Eficiência Operacional:</strong> Centralize todas as informações do projeto para reduzir retrabalho e melhorar a coordenação entre equipes.</p>
        </div>
        <div class="benefit">
            <i class='bx bx-error'></i>
            <p><strong style="font-family: 'Manrope', sans-serif;">Identificação Precoce de Problemas:</strong> Detecte atrasos e problemas rapidamente para implementar medidas corretivas ágeis e evitar gastos desnecessários.</p>
        </div>
        <div class="benefit">
            <i class='bx bx-shield'></i>
            <p><strong style="font-family: 'Manrope', sans-serif;">Transparência e Confiança:</strong> Promova uma comunicação mais clara e transparente com clientes, oferecendo acesso fácil e seguro aos dados do projeto.</p>
        </div>
    </div>
    <div class="benefits-image">
        <img src="imgs/benefcios.png" alt="Imagem Representativa">
    </div>
</section>

<section class="cta-section">
    <div class="cta-content">
        <h2>Pronto Para Transformar Seus Projetos?</h2>
        <p>Cadastre-se agora para começar seus projetos ou entre em contato para mais informações.</p>
        <button class="cta-btn" onclick="window.location.href='cadastro/cademp.php'">Comece Agora!</button>
    </div>
</section>

<footer class="footerlp">
    <div class="footer-left">
        <img src="imgs/obraplanner7.png" alt="Logo" class="footer-logo">
    </div>
    <div class="footer-right">
        <div class="footer-links">
            <a href="#">Termos de Serviço</a>
            <a href="#">Política de Privacidade</a>
        </div>
        <div class="footer-links">
        <a href="mailto:obraplanner@gmail.com"><i class='bx bx-mail-send'></i> obraplanner@gmail.com</a>
        <a href="#" target="_blank"><i class='bx bxl-instagram'></i> Instagram</a>
        <a href="#" target="_blank"><i class='bx bxl-twitter'></i> Twitter</a>
        <a href="#" target="_blank"><i class='bx bxl-linkedin'></i> LinkedIn</a>
        </div>
    </div>
</footer>
<script>
    document.querySelectorAll('.scroll-to').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); 
            const targetClass = this.getAttribute('data-target');
            const targetElement = document.querySelector(targetClass);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>
</body>
</html>
