-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para obra_planner
CREATE DATABASE IF NOT EXISTS `obra_planner` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `obra_planner`;

-- Copiando estrutura para tabela obra_planner.admin
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela obra_planner.admin: ~0 rows (aproximadamente)
REPLACE INTO `admin` (`admin_id`, `nome`, `email`, `senha`, `foto`) VALUES
	(1, 'Maria Rafaela do Nascimento de Souza', 'admin@example.com', '$2y$10$5cqYmiLgZNzHef8DINtGN.JqxNqNpW7oO.CjxCpgpoMqmOwC2qIb.', 'obraplanner3.png');

-- Copiando estrutura para tabela obra_planner.empresas
CREATE TABLE IF NOT EXISTS `empresas` (
  `empresa_id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cnpj` varchar(20) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `responsavel` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `ativa` enum('ativa','desativada') DEFAULT 'ativa',
  `plano_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`empresa_id`),
  UNIQUE KEY `nome` (`nome`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `cnpj` (`cnpj`),
  KEY `plano_id` (`plano_id`),
  CONSTRAINT `empresas_ibfk_1` FOREIGN KEY (`plano_id`) REFERENCES `planos` (`plano_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela obra_planner.empresas: ~2 rows (aproximadamente)
REPLACE INTO `empresas` (`empresa_id`, `nome`, `email`, `telefone`, `endereco`, `cnpj`, `senha`, `responsavel`, `logo`, `ativa`, `plano_id`) VALUES
	(27, 'Carioca Construção', 'cariocacons@gmail.com', '(21) 99727-6456', 'R. São Clemente - Botafogo', '12.121.212/1212-21', '$2y$10$GLs3PF99.DGV8iQQ7RutxOzpqAYYFtaJ1CXFbGcVENtPOZ4nxMmoS', 'Henrique dos Santos', 'logoemp.avif', 'ativa', 1),
	(28, 'Bighit Enteriment', 'bighitenteriment2013@gmail.com', '(30) 30303-0303', 'Rua José Luis de Oliveira', '79.707.087/0701-23', '$2y$10$159bng8ni5QdEqSZJBFniu7r.6QoyguW5AnhjhmHwz6YcHDIik9Ba', 'Bang Si-hyuk', 'alexlove.jpg', 'desativada', 2);

-- Copiando estrutura para tabela obra_planner.etapas
CREATE TABLE IF NOT EXISTS `etapas` (
  `etapa_id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `observacoes` text DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `projeto_id` int(11) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_termino` date NOT NULL,
  `data_previa_inicio` date DEFAULT NULL,
  `data_previa_termino` date DEFAULT NULL,
  `concluida` tinyint(1) DEFAULT 0,
  `mensagem` varchar(225) DEFAULT NULL,
  PRIMARY KEY (`etapa_id`),
  KEY `projeto_id` (`projeto_id`),
  CONSTRAINT `etapas_ibfk_1` FOREIGN KEY (`projeto_id`) REFERENCES `projetos` (`projeto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela obra_planner.etapas: ~14 rows (aproximadamente)
REPLACE INTO `etapas` (`etapa_id`, `titulo`, `descricao`, `observacoes`, `imagem`, `projeto_id`, `data_inicio`, `data_termino`, `data_previa_inicio`, `data_previa_termino`, `concluida`, `mensagem`) VALUES
	(45, 'Preparação do Ambiente', 'Desmontagem dos móveis existentes e proteção das áreas que não serão reformadas.\r\nLimpeza do espaço para iniciar as reformas.', 'Certifique-se de proteger móveis e objetos próximos para evitar danos.\r\nAvalie a necessidade de remover tomadas ou interruptores antes de iniciar.', 'projeto_casa_moderna.jpg', 38, '2024-11-02', '2024-11-03', '2024-11-02', '2024-11-03', 0, NULL),
	(46, 'Pintura das Paredes', 'Aplicação de primer e pintura das paredes com tinta acrílica de alta resistência.\r\nSecagem e retoques, se necessário.', 'Escolha cores que harmonizem com o mobiliário planejado.\nVerifique a ventilação para facilitar a secagem da pintura.Certifique-se de que o novo piso seja adequado ao nível de tráfego do quarto.Verifique a ventilação para facilitar a secagem da pintura.', NULL, 38, '2024-11-04', '2024-11-06', '2024-11-04', '2024-11-06', 0, NULL),
	(47, 'Troca do Piso', 'Remoção do piso antigo e preparação do substrato.\r\nInstalação do novo piso de vinílico.', 'Garanta que o piso antigo seja removido sem comprometer o contrapiso.\nCertifique-se de que o novo piso seja adequado ao nível de tráfego do quarto.', NULL, 38, '2024-11-07', '2024-11-11', '2024-11-07', '2024-11-11', 0, NULL),
	(48, 'Instalação de Móveis Planejados', 'Projeto e montagem de móveis sob medida, incluindo armários, estante e mesa de cabeceira.\r\nInstalação e ajustes para garantir a funcionalidade e acabamento adequado.', 'Confirme as medidas do espaço antes de instalar os móveis.\nInspecione o acabamento para evitar problemas futuros.', NULL, 38, '2024-11-12', '2024-11-13', '2024-11-12', '2024-11-13', 0, NULL),
	(49, 'Substituição da Iluminação e Cortinas', 'Troca das luminárias por modelos modernos e eficientes.\r\nInstalação de novas cortinas e ajuste dos trilhos.', 'Opte por luminárias com controle de intensidade para maior conforto.\nEscolha cortinas que combinem com a funcionalidade e o estilo desejado.', NULL, 38, '2024-11-14', '2024-11-15', '2024-11-14', '2024-11-15', 0, NULL),
	(53, 'Planejamento e Acompanhamento do Projeto', 'Definição do escopo da reforma, levantamento de necessidades, seleção de fornecedores e criação de cronograma detalhado.', 'Fundamental para garantir que todas as necessidades sejam mapeadas e o cronograma seja executado conforme planejado.', NULL, 40, '2024-01-02', '2024-01-06', '2024-01-02', '2024-01-06', 0, NULL),
	(54, 'Desmobilização e Preparação do Local', ' Retirada de móveis, equipamentos e materiais existentes, além de preparação para as obras (como demarcação de áreas e proteção de pisos).', 'Uma etapa crítica para evitar danos e preparar o espaço para as reformas, minimizando problemas futuros.', NULL, 40, '2024-01-07', '2024-01-10', '2024-01-07', '2024-01-10', 0, NULL),
	(55, 'Reformas Estruturais e Elétricas', 'Alterações no layout das paredes, adequação do sistema elétrico e de cabos de rede, além da instalação de novos pontos de energia.', 'Envolve intervenções que precisam ser bem executadas para garantir a segurança e funcionalidade do espaço.', NULL, 40, '2024-01-11', '2024-01-20', '2024-01-11', '2024-01-20', 0, NULL),
	(56, 'Instalação de Ar Condicionado e Ventilação', 'Substituição do sistema de ar condicionado, instalação de novas unidades de ventilação e ajustes no sistema de climatização.', 'Deve ser realizada por técnicos especializados para assegurar a eficiência e durabilidade do sistema.', NULL, 40, '2024-01-21', '2024-01-25', '2024-01-21', '2024-01-25', 0, NULL),
	(57, 'Pintura e Acabamentos', 'Pintura das paredes e tetos, acabamento de rodapés e ajustes finais na estrutura.', 'A qualidade dos acabamentos impacta diretamente na estética e sensação de renovação do ambiente.', NULL, 40, '2024-01-26', '2024-02-05', '2024-01-26', '2024-02-05', 0, NULL),
	(58, 'Mobiliação e Decoração', 'Colocação de novos móveis, decoração do ambiente e organização final do espaço.', ' Etapa final que dá identidade ao espaço e deve ser feita com atenção aos detalhes para um resultado harmonioso.', NULL, 40, '2024-02-06', '2024-02-10', '2024-02-06', '2024-02-10', 0, NULL),
	(60, 'preparação do terreno e fundação', 'Limpeza do terreno, demarcação da obra, escavação para a fundação e execução da fundação (radier e sapatas).', NULL, NULL, 41, '2024-12-01', '2024-12-01', '2024-12-01', '2024-12-01', 0, NULL),
	(61, 'estrutura e alvenaria', 'Levantamento das paredes, instalação de vigas e lajes, execução do telhado e ajustes estruturais.', NULL, NULL, 41, '2024-12-01', '2024-12-01', '2024-12-01', '2024-12-01', 0, NULL),
	(62, 'acabamento e instalações', 'Realização de instalações elétricas, hidráulicas e de esgoto, revestimento de pisos e paredes, pintura, instalação de portas e janelas e acabamento geral.', NULL, NULL, 41, '2024-12-01', '2024-12-01', '2024-12-01', '2024-12-01', 0, NULL);

-- Copiando estrutura para tabela obra_planner.fiscais
CREATE TABLE IF NOT EXISTS `fiscais` (
  `fiscal_id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `empresa_id` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `ativa` enum('ativa','desativada') DEFAULT 'ativa',
  PRIMARY KEY (`fiscal_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `cpf` (`cpf`),
  KEY `empresa_id` (`empresa_id`),
  CONSTRAINT `fiscais_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`empresa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela obra_planner.fiscais: ~3 rows (aproximadamente)
REPLACE INTO `fiscais` (`fiscal_id`, `nome`, `email`, `telefone`, `senha`, `cpf`, `empresa_id`, `foto`, `ativa`) VALUES
	(14, 'Joana De Lourdes Nascimento de Souza', 'joanadelurdesnas@gmail.com', '(21) 99727-6456', '$2y$10$J9eH/vf3RrrZvDPq0NJ4cuNbWotZrCmQHh77lZH1oH095diNCd6e6', '123.456.789-05', 27, 'cheerful-optimsitic-young-female-student-with-curly-dark-hairstyle-yellow-sweater-smiling-laughing-happily-pointing-fingers-up-showing-friends-link-site-copy-space-white-wall.jpg', 'ativa'),
	(15, 'Rodolfo Bartholomeu', 'rbrs1966@gmail.com', '(12) 13213-123', '$2y$10$b3aX/A0KvADFXEiE2wgJve3UNPHSXjtgoCFvUJQbWe8lY7bajRgC.', '123.456.789-00', 27, 'fiscal.jpg', 'ativa'),
	(16, 'Maria Vitória do Nascimento de Souza', 'mv150805@gmail.com', '(30) 30303-0303', '$2y$10$13mUTJOUq.YpOJDgg0dOWOUz66CBsIYJ6suaN6hJOljA1C4qBb7H.', '288.282.882-82', 27, 'fiscal.jpg', 'desativada');

-- Copiando estrutura para tabela obra_planner.funcionarios
CREATE TABLE IF NOT EXISTS `funcionarios` (
  `func_id` varchar(225) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `cpf` varchar(15) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `empresa_id` int(11) DEFAULT NULL,
  `horas_trabalhadas` decimal(5,2) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `ativa` enum('ativa','desativada') DEFAULT 'ativa',
  PRIMARY KEY (`func_id`),
  KEY `empresa_id` (`empresa_id`),
  CONSTRAINT `funcionarios_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`empresa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela obra_planner.funcionarios: ~5 rows (aproximadamente)
REPLACE INTO `funcionarios` (`func_id`, `nome`, `email`, `telefone`, `cpf`, `senha`, `empresa_id`, `horas_trabalhadas`, `foto`, `ativa`) VALUES
	('1', 'Juliana Martins Lopes', 'juliana.lopes@gmail.com', '(51) 91234-8765', '567.890.123-44', '$2y$10$UeGt67zw5ZfrpnMoFM0/Re3Oi3jUNyc8rdFv/0cca59jfevP7RY9K', 27, 2.00, 'lifestyle-people-emotions-casual-concept-confident-nice-smiling-asian-woman-cross-arms-chest-confident-ready-help-listening-coworkers-taking-part-conversation.jpg', 'ativa'),
	('2', 'Ana Paula Silva', 'ana.silva@gmail.com', '(12) 34567-8900', '119.123.456-78', '$2y$10$jIGbP60aCw.i/c5ATo0v8eyoHPNctiHfI18wFoHhxDpTdvz4jKXR.', 27, 908.00, 'func.jpg', 'ativa'),
	('3', 'João Carlos Souza', 'joao.souza@gmail.com', '(21) 98765-4321', '234.567.890-11', '$2y$10$6Eg3ha2lEffj9atXjD6Rw.zwRtXzH2rEo8HjTdF3FqNFLXkIdX2LK', 27, 750.00, 'func.jpg', 'desativada'),
	('4', 'Maria Fernanda Costa', 'maria.costa@gmail.com', '(34) 56789-0122', '319.987.654-32', '$2y$10$850iySNETJoGETSDNiwnm.UZBVwDAL.XEPFQn.zt1exMthoHg.QTq', 27, 4.00, 'func.jpg', 'ativa'),
	('5', 'Pedro Henrique Almeida', 'pedro.almeida@gmail.com', '(41) 98765-0987', '456.789.012-33', '$2y$10$w.u4W2U5siF1nUywnmtRcu2QhqP3FpJd0tNxBKaHnth5WDHnIdn5C', 27, 3.00, 'func.jpg', 'desativada');

-- Copiando estrutura para tabela obra_planner.logs
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usu_id` int(11) NOT NULL,
  `acao` varchar(255) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usu_id` (`usu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela obra_planner.logs: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela obra_planner.notificacoes
CREATE TABLE IF NOT EXISTS `notificacoes` (
  `notificacao_id` int(11) NOT NULL AUTO_INCREMENT,
  `usu_id` int(11) NOT NULL,
  `tipo_usuario` enum('empresa','fiscal','funcionario','admin') NOT NULL,
  `tipo_notificacao` enum('comentario_empresa','projeto_concluido','atraso_projeto','comentario_funcionario','comentario_fiscal','presenca_confirmada','novo_usuario_empresa','novo_usuario_fiscal','novo_usuario_funcionario','novo_projeto','comentario_sobre_plataforma','pergunta','usuario_ativado') NOT NULL,
  `mensagem` text NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `visualizada` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`notificacao_id`),
  KEY `usu_id` (`usu_id`),
  CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`usu_id`) REFERENCES `empresas` (`empresa_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela obra_planner.notificacoes: ~2 rows (aproximadamente)
REPLACE INTO `notificacoes` (`notificacao_id`, `usu_id`, `tipo_usuario`, `tipo_notificacao`, `mensagem`, `data_criacao`, `visualizada`) VALUES
	(2, 27, 'empresa', 'novo_projeto', 'Novo projeto criado: Construção de uma Casa Residencial.', '2024-12-01 19:22:02', 0),
	(3, 27, 'empresa', 'novo_usuario_fiscal', 'Um novo fiscal foi cadastrado: Rodolfo Bartholomeu', '2024-12-02 00:45:01', 0),
	(4, 27, 'empresa', 'novo_usuario_funcionario', 'Novo funcionário cadastrado: Pedro Henrique Almeida', '2024-12-01 20:48:17', 0);

-- Copiando estrutura para tabela obra_planner.planos
CREATE TABLE IF NOT EXISTS `planos` (
  `plano_id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `descricao` text NOT NULL,
  `funcionalidades` text NOT NULL,
  `preco_mensal` decimal(10,2) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`plano_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela obra_planner.planos: ~2 rows (aproximadamente)
REPLACE INTO `planos` (`plano_id`, `nome`, `descricao`, `funcionalidades`, `preco_mensal`, `criado_em`, `atualizado_em`) VALUES
	(1, 'Plano Básico', 'Ideal para pequenas empresas de construção que precisam de funcionalidades essenciais para iniciar e gerenciar projetos.', 'Criação e gestão de até 3 projetos simultâneos, Acompanhamento de cronogramas e etapas do projeto, Controle de presença para até 15 funcionários, Cadastro de até 2 fiscais e 3 equipes, Relatórios simples de progresso e tarefas', 99.00, '2024-08-14 15:16:16', '2024-08-14 15:16:16'),
	(2, 'Plano Premium', 'Perfeito para médias e grandes empresas de construção que precisam de ferramentas completas e suporte dedicado para projetos complexos.', 'Inclui todas as funcionalidades do Plano Básico, Gestão de até 10 projetos simultâneos, Controle de presença para até 50 funcionários, Cadastro de até 5 fiscais e 10 equipes, Comunicação avançada com integrações (e.g., CRM, Email)', 299.00, '2024-08-14 15:16:16', '2024-08-14 15:16:16');

-- Copiando estrutura para tabela obra_planner.presenca
CREATE TABLE IF NOT EXISTS `presenca` (
  `presenca_id` int(11) NOT NULL AUTO_INCREMENT,
  `func_id` varchar(225) DEFAULT NULL,
  `checkin` datetime NOT NULL,
  `checkout` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `data` datetime NOT NULL,
  PRIMARY KEY (`presenca_id`),
  KEY `func_id` (`func_id`),
  CONSTRAINT `presenca_ibfk_1` FOREIGN KEY (`func_id`) REFERENCES `funcionarios` (`func_id`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela obra_planner.presenca: ~0 rows (aproximadamente)
REPLACE INTO `presenca` (`presenca_id`, `func_id`, `checkin`, `checkout`, `created_at`, `data`) VALUES
	(158, '1', '2024-11-27 18:16:51', '2024-11-27 18:16:51', '2024-11-27 21:16:51', '2024-11-27 00:00:00');

-- Copiando estrutura para tabela obra_planner.projetos
CREATE TABLE IF NOT EXISTS `projetos` (
  `projeto_id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `documentacao` varchar(255) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_termino` date DEFAULT NULL,
  `data_prev_ini` date DEFAULT NULL,
  `data_prev_ter` date DEFAULT NULL,
  `empresa_id` int(11) DEFAULT NULL,
  `fiscal_id` int(11) DEFAULT NULL,
  `func_id` varchar(225) DEFAULT NULL,
  `status` enum('Em andamento','Concluído','Atrasado') NOT NULL DEFAULT 'Em andamento',
  `qtn_eta` varchar(100) DEFAULT NULL,
  `progresso` int(11) DEFAULT NULL,
  PRIMARY KEY (`projeto_id`),
  KEY `empresa_id` (`empresa_id`),
  KEY `fiscal_id` (`fiscal_id`),
  KEY `func_id` (`func_id`),
  CONSTRAINT `projetos_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`empresa_id`),
  CONSTRAINT `projetos_ibfk_2` FOREIGN KEY (`fiscal_id`) REFERENCES `fiscais` (`fiscal_id`),
  CONSTRAINT `projetos_ibfk_3` FOREIGN KEY (`func_id`) REFERENCES `funcionarios` (`func_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela obra_planner.projetos: ~3 rows (aproximadamente)
REPLACE INTO `projetos` (`projeto_id`, `titulo`, `descricao`, `imagem`, `documentacao`, `data_inicio`, `data_termino`, `data_prev_ini`, `data_prev_ter`, `empresa_id`, `fiscal_id`, `func_id`, `status`, `qtn_eta`, `progresso`) VALUES
	(38, 'Reforma do Quarto', 'Reforma planejada para modernizar e otimizar o espaço do quarto, incluindo pintura, instalação de móveis planejados e melhorias na iluminação.', '3d-contemporary-living-room-interior-modern-furniture.jpg', 'uploads/Reforma_Quarto_Principal.pdf', '2024-01-02', '2024-01-15', '2024-01-02', '2024-01-15', 27, 14, NULL, 'Em andamento', '6', NULL),
	(40, 'Reforma de Escritório Comercial', 'Reforma e modernização de um escritório comercial para melhorar a funcionalidade, estética e conformidade com as normas de acessibilidade. O projeto envolve a reconfiguração do layout, modernização das instalações elétricas e de ar condicionado, melhorias na iluminação, pintura e troca de móveis.', 'reformaescritorio.jpg', 'Reforma_Escritório_Comercial.pdf', '2024-01-02', '2024-03-15', '2024-01-02', '2024-03-15', 27, 16, NULL, 'Em andamento', '6', NULL),
	(41, 'Construção de uma Casa Residencial', 'Construção de uma casa residencial de 150m², com 3 quartos, 2 banheiros, sala de estar, cozinha e garagem. O projeto inclui todas as fases da obra, desde a preparação do terreno até a finalização das instalações e acabamentos.\r\netapas', 'uploads/projeto_casa_moderna.jpg', 'uploads/projeto_casa_moderna.pdf', '2024-12-01', '2024-12-01', '2024-12-01', '2024-12-01', 27, 15, NULL, 'Em andamento', '3', NULL);

-- Copiando estrutura para tabela obra_planner.projeto_funcionarios
CREATE TABLE IF NOT EXISTS `projeto_funcionarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projeto_id` int(11) NOT NULL,
  `func_id` varchar(225) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `projeto_id` (`projeto_id`),
  KEY `func_id` (`func_id`),
  CONSTRAINT `projeto_funcionarios_ibfk_1` FOREIGN KEY (`projeto_id`) REFERENCES `projetos` (`projeto_id`),
  CONSTRAINT `projeto_funcionarios_ibfk_2` FOREIGN KEY (`func_id`) REFERENCES `funcionarios` (`func_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela obra_planner.projeto_funcionarios: ~8 rows (aproximadamente)
REPLACE INTO `projeto_funcionarios` (`id`, `projeto_id`, `func_id`) VALUES
	(20, 38, '1'),
	(21, 38, '2'),
	(22, 38, '3'),
	(28, 40, '1'),
	(29, 40, '2'),
	(30, 40, '3'),
	(31, 41, '1'),
	(32, 41, '2');

-- Copiando estrutura para tabela obra_planner.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `usu_id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('Empresa','Fiscal','Funcionário') NOT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`usu_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela obra_planner.usuarios: ~10 rows (aproximadamente)
REPLACE INTO `usuarios` (`usu_id`, `nome`, `email`, `senha`, `tipo`, `data_cadastro`) VALUES
	(12, 'Carioca Construção', 'cariocacons@gmail.com', '$2y$10$GLs3PF99.DGV8iQQ7RutxOzpqAYYFtaJ1CXFbGcVENtPOZ4nxMmoS', 'Empresa', '2024-12-01 15:48:45'),
	(13, 'Joana De Lourdes Nascimento de Souza', 'joanadelurdesnas@gmail.com', '$2y$10$J9eH/vf3RrrZvDPq0NJ4cuNbWotZrCmQHh77lZH1oH095diNCd6e6', 'Fiscal', '2024-12-01 15:48:45'),
	(14, 'Rodolfo Bartholomeu', 'rbrs1966@gmail.com', '$2y$10$b3aX/A0KvADFXEiE2wgJve3UNPHSXjtgoCFvUJQbWe8lY7bajRgC.', 'Fiscal', '2024-12-01 15:48:45'),
	(15, 'Juliana Martins Lopes', 'juliana.lopes@gmail.com', '$2y$10$UeGt67zw5ZfrpnMoFM0/Re3Oi3jUNyc8rdFv/0cca59jfevP7RY9K', 'Funcionário', '2024-12-01 15:48:45'),
	(16, 'Ana Paula Silva', 'ana.silva@gmail.com', '$2y$10$jIGbP60aCw.i/c5ATo0v8eyoHPNctiHfI18wFoHhxDpTdvz4jKXR.', 'Funcionário', '2024-12-01 15:48:45'),
	(17, 'João Carlos Souza', 'joao.souza@gmail.com', '$2y$10$6Eg3ha2lEffj9atXjD6Rw.zwRtXzH2rEo8HjTdF3FqNFLXkIdX2LK', 'Funcionário', '2024-12-01 15:48:45'),
	(18, 'Maria Fernanda Costa', 'maria.costa@gmail.com', '$2y$10$850iySNETJoGETSDNiwnm.UZBVwDAL.XEPFQn.zt1exMthoHg.QTq', 'Funcionário', '2024-12-01 15:48:45'),
	(19, 'Pedro Henrique Almeida', 'pedro.almeida@gmail.com', '$2y$10$w.u4W2U5siF1nUywnmtRcu2QhqP3FpJd0tNxBKaHnth5WDHnIdn5C', 'Funcionário', '2024-12-01 15:48:45'),
	(20, 'Maria Vitória do Nascimento de Souza', 'mv150805@gmail.com', '$2y$10$13mUTJOUq.YpOJDgg0dOWOUz66CBsIYJ6suaN6hJOljA1C4qBb7H.', 'Fiscal', '2024-12-01 15:48:45'),
	(21, 'Bighit Enteriment', 'bighitenteriment2013@gmail.com', '$2y$10$159bng8ni5QdEqSZJBFniu7r.6QoyguW5AnhjhmHwz6YcHDIik9Ba', 'Empresa', '2024-12-01 15:48:45');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
