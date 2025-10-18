-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tempo de Geração: 09/06/2025 às 18h53min
-- Versão do Servidor: 5.5.20
-- Versão do PHP: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `escola`
--
CREATE DATABASE `escola` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `escola`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `aluno`
--

CREATE TABLE IF NOT EXISTS `aluno` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `fone` int(9) NOT NULL,
  `codcurso` int(5) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `aluno`
--

INSERT INTO `aluno` (`codigo`, `nome`, `fone`, `codcurso`) VALUES
(1, 'renan', 123, 1),
(3, 'marcus', 789, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `coordenador`
--

CREATE TABLE IF NOT EXISTS `coordenador` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `coordenador`
--

INSERT INTO `coordenador` (`codigo`, `nome`) VALUES
(1, 'max'),
(2, 'fernando');

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso`
--

CREATE TABLE IF NOT EXISTS `curso` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `codcoord` int(5) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `curso`
--

INSERT INTO `curso` (`codigo`, `nome`, `codcoord`) VALUES
(1, 'informatica', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `login` varchar(10) NOT NULL,
  `senha` varchar(10) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
--
-- Banco de Dados: `livraria`
--
CREATE DATABASE `livraria` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `livraria`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `autor`
--

CREATE TABLE IF NOT EXISTS `autor` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `pais` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Extraindo dados da tabela `autor`
--

INSERT INTO `autor` (`codigo`, `nome`, `pais`) VALUES
(1, 'J.K. Rowling', 'Reino Unido'),
(2, 'George Orwell', 'Reino Unido'),
(3, 'Isaac Asimov', 'Estados Unidos'),
(4, 'Agatha Christie', 'Reino Unido'),
(5, 'J.R.R. Tolkien', 'Reino Unido'),
(6, 'Stephen King', 'Estados Unidos'),
(7, 'Gabriel García Márquez', 'Colômbia'),
(8, 'Mark Twain', 'Estados Unidos'),
(9, 'Haruki Murakami', 'Japão');

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`codigo`, `nome`) VALUES
(1, 'Fantasia'),
(2, 'Ficção Científica'),
(3, 'Distopia'),
(4, 'Mistério'),
(5, 'Aventura'),
(6, 'Romance'),
(7, 'Horror'),
(8, 'Thriller'),
(9, 'Histórico');

-- --------------------------------------------------------

--
-- Estrutura da tabela `editora`
--

CREATE TABLE IF NOT EXISTS `editora` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Extraindo dados da tabela `editora`
--

INSERT INTO `editora` (`codigo`, `nome`) VALUES
(1, 'Bloomsbury'),
(2, 'Penguin Random House'),
(3, 'HarperCollins'),
(4, 'Editora Record'),
(5, 'Companhia das Letras'),
(6, 'Harper & Row'),
(7, 'Editora Aleph'),
(8, 'Rocco'),
(9, 'Leya');

-- --------------------------------------------------------

--
-- Estrutura da tabela `livro`
--

CREATE TABLE IF NOT EXISTS `livro` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `nrpaginas` int(4) NOT NULL,
  `ano` int(4) NOT NULL,
  `codautor` int(5) NOT NULL,
  `codcategoria` int(5) NOT NULL,
  `codeditora` int(5) NOT NULL,
  `resenha` text NOT NULL,
  `preco` float(6,2) NOT NULL,
  `fotocapa1` varchar(100) NOT NULL,
  `fotocapa2` varchar(100) NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `codcategoria` (`codcategoria`),
  KEY `codeditora` (`codeditora`),
  KEY `codautor` (`codautor`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Extraindo dados da tabela `livro`
--

INSERT INTO `livro` (`codigo`, `titulo`, `nrpaginas`, `ano`, `codautor`, `codcategoria`, `codeditora`, `resenha`, `preco`, `fotocapa1`, `fotocapa2`) VALUES
(1, 'Harry Potter e a Pedra Filosofal', 223, 1997, 1, 1, 8, 'O inÃ­cio da jornada mÃ¡gica de Harry Potter na escola de magia e bruxaria de Hogwarts.', 59.00, '4d09fa2e2fd205a81e404d9e8d2f0338.webp', '6cb7f14b4b0e134079fa0b81c5a9c427.webp'),
(2, '1984', 328, 1949, 2, 3, 5, 'Uma crÃ­tica ao totalitarismo e Ã  vigilÃ¢ncia extrema do Estado.', 39.00, '6ee29f6a2996cf95d1c905151b35ee28.jpg', 'cbb6e9243378503369af0a0afcc45fd2.jpg'),
(3, 'FundaÃ§Ã£o', 256, 1951, 3, 2, 7, 'Cientista prevÃª a queda do ImpÃ©rio GalÃ¡ctico e busca salvar o conhecimento da humanidade.', 54.00, '8fb954ae8e8454c29cb9ecf2682a1a53.jpg', '772a924fb04fa3f65e2f4162a8d76055.jpg'),
(4, 'Assassinato no Expresso do Oriente', 288, 1934, 4, 4, 3, 'Um assassinato acontece em um trem de luxo e o detetive Poirot precisa desvendar o caso.', 44.90, 'd872514d4490f6ed0db28afab5705245.jpg', 'da5dc85c1b172959d4e489e2874646e5.jpg'),
(5, 'O Senhor dos AnÃ©is: A Sociedade do Anel', 576, 1954, 5, 1, 3, 'A jornada Ã©pica de Frodo para destruir o Um Anel.', 89.00, '082866ae2ea380cbb533e98c884f581e.jpg', '3ff405c17b7c0b1cc485b8ee2df59cf5.jpg'),
(6, 'O Iluminado', 476, 1977, 6, 7, 5, 'Um hotel isolado desperta os piores instintos em um homem atormentado.', 49.00, '59d8c044d2db1bb478fb295eddda1168.jpg', '2260b03e6f01ccda30d33f9b65018657.jpg'),
(7, 'Cem Anos de SolidÃ£o', 432, 1967, 7, 6, 4, 'A saga da famÃ­lia BuendÃ­a na cidade fictÃ­cia de Macondo.', 59.00, '92e4f8edf0241e1326f91013fc1386bb.jpg', 'f692a6b080f4efbbfb812fc71360187e.jpg'),
(8, 'As Aventuras de Tom Sawyer', 274, 1876, 8, 5, 2, 'A infÃ¢ncia travessa de Tom Sawyer Ã s margens do rio Mississippi.', 34.00, 'e9e94e2b0e1d8e1cb55b470b1b627740.jpg', '6b5a71c3f57e96c7937e0a15b24d8f29.jpg'),
(9, 'Kafka Ã  Beira-Mar', 480, 2002, 9, 8, 5, 'Um garoto foge de casa e se envolve em uma jornada surreal.', 69.00, 'b7f2f490023b3fa60ac6b89c3069ec7a.jpg', 'f5c94dd1c23b026177c1648515a33e94.jpg'),
(10, 'Harry Potter e a CÃ¢mara Secreta', 251, 1998, 1, 1, 8, 'AlguÃ©m libertou um monstro em Hogwarts e Harry precisa descobrir quem.', 59.00, 'ff58931ff616a8eeed6644d9d0c990d5.jpg', '088786eef7dd22d011b06c4c5ba090c5.jpg'),
(11, 'RevoluÃ§Ã£o dos Bichos', 112, 1945, 2, 3, 5, ' Uma sÃ¡tira polÃ­tica sobre uma revoluÃ§Ã£o animal que vira ditadura.', 34.00, '8f69e1af5abe22dd988a6a5d8e5247c5.jpg', '66a09898b2628b90fbc6927a18a3221a.jpg'),
(12, 'Eu, RobÃ´', 224, 1950, 3, 2, 7, 'ColetÃ¢nea de contos que exploram a relaÃ§Ã£o entre humanos e robÃ´s.', 49.00, '426f5e19a37b61c8c06c767e011e992f.jpg', 'bd56e0bd052a919a07f65ec9215cd732.jpg'),
(13, 'E NÃ£o Sobrou Nenhum', 272, 1939, 4, 4, 3, 'Dez pessoas presas em uma ilha sÃ£o mortas uma a uma.', 42.00, '15e49ad634ea88cd18f56b93553e451e.jpg', '6bb31e7fafdcef163db0a8728f52f03f.jpg'),
(14, 'O Hobbit', 320, 1937, 5, 1, 3, 'A aventura de Bilbo Bolseiro atÃ© a Montanha SolitÃ¡ria.', 59.00, 'be6a440854dc76597fb3a14fa48723c4.jpg', '990606868ab35711b376a2396da61055.jpg'),
(15, 'It: A Coisa', 1104, 1986, 6, 7, 6, 'Um grupo enfrenta um ser maligno que assume vÃ¡rias formas.', 89.00, '861f5cc30b2b950f6da232c50c6a56e2.jpg', '7656500058454261333f777f28fb3d68.jpg'),
(16, 'O Amor nos Tempos do CÃ³lera', 368, 1985, 7, 6, 4, 'Uma histÃ³ria de amor que resiste ao tempo e Ã s adversidades.', 49.00, '5460d5ffd656a48a6b78da075e90a111.jpg', 'ff748080b6b7bb4d60191055fcbf5873.jpg'),
(17, 'O PrÃ­ncipe e o Mendigo', 192, 1881, 8, 9, 2, 'Dois garotos idÃªnticos trocam de lugar e descobrem os desafios da vida alheia.', 29.00, '8cc2f912e8193ffb85e249196e28017f.jpg', 'ff2e4d7ad5fbd5aeef3e81facf971676.jpg'),
(19, 'Norwegian Wood', 296, 1987, 9, 6, 5, 'Um jovem revive suas lembranÃ§as de juventude e amores perdidos.', 54.00, '5f9c9d0f78085a0a71a1a6c3558698f2.jpg', 'a012663db162bc62a7891f3a0388212f.jpg'),
(20, 'Harry Potter e o Prisioneiro de Azkaban', 318, 1999, 1, 1, 8, 'Um fugitivo perigoso ameaÃ§a Hogwarts e Harry descobre verdades sobre seu passado.', 59.00, '6b437611cc0b862c8604828466256af4.jpg', '3d21ec4274dd6697f8838659c5efd44f.jpg'),
(21, 'A DanÃ§a da Morte', 864, 1978, 6, 3, 6, 'ApÃ³s uma epidemia, sobreviventes enfrentam o bem e o mal em um novo mundo.', 74.00, '00ce715a797b05b6c210793a523f7db6.jpg', '50179a6d9956ba0f3eb1920992d78fe9.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `senha` varchar(10) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`codigo`, `login`, `senha`) VALUES
(1, 'teste', '1234');

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `livro`
--
ALTER TABLE `livro`
  ADD CONSTRAINT `livro_ibfk_1` FOREIGN KEY (`codautor`) REFERENCES `autor` (`codigo`),
  ADD CONSTRAINT `livro_ibfk_2` FOREIGN KEY (`codcategoria`) REFERENCES `categoria` (`codigo`),
  ADD CONSTRAINT `livro_ibfk_3` FOREIGN KEY (`codeditora`) REFERENCES `editora` (`codigo`);
--
-- Banco de Dados: `loja`
--
CREATE DATABASE `loja` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `loja`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`codigo`, `nome`) VALUES
(1, 'casual'),
(4, 'esportivo'),
(5, 'caminhada'),
(6, 'd'),
(7, 'd'),
(8, 'd'),
(9, 'd');

-- --------------------------------------------------------

--
-- Estrutura da tabela `marca`
--

CREATE TABLE IF NOT EXISTS `marca` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Extraindo dados da tabela `marca`
--

INSERT INTO `marca` (`codigo`, `nome`) VALUES
(8, 'nike'),
(9, 'adidas'),
(13, 'tesla'),
(14, 'new balance');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE IF NOT EXISTS `produto` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  `cor` varchar(50) NOT NULL,
  `tamanho` varchar(15) NOT NULL,
  `preco` float(10,2) NOT NULL,
  `codmarca` int(5) NOT NULL,
  `codcategoria` int(5) NOT NULL,
  `codtipo` int(5) NOT NULL,
  `foto1` varchar(100) NOT NULL,
  `foto2` varchar(100) NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `codmarca` (`codmarca`),
  KEY `codcategoria` (`codcategoria`),
  KEY `codtipo` (`codtipo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Extraindo dados da tabela `produto`
--

INSERT INTO `produto` (`codigo`, `descricao`, `cor`, `tamanho`, `preco`, `codmarca`, `codcategoria`, `codtipo`, `foto1`, `foto2`) VALUES
(24, 'TÃªnis New Balance 515 V2 Masculino', 'preto e branco', '42', 339.00, 14, 1, 4, 'a7e4a93705c042020fe556f0ea42755d', '14c2c9809b40634dd5b9ce9fb6a7201f'),
(25, 'TÃªnis Nike Court Shot Masculino', 'branco', '42', 499.00, 8, 1, 4, '9f99f0d1e4127d93e3d8ccac24024bb8', '2f354a5af0f6b3cb370022939b3eeee8'),
(26, 'TÃªnis Nike Air Force 1', 'bege', '42', 699.00, 8, 1, 4, '168de0bf064ac04ee67d239573e26c8e', '62d4b3d6d1c8edae558d5ce5d540c1bb'),
(27, 'Nike Campo Masculina Tiempo Legend 10 Club', 'rosa', '42', 399.00, 8, 4, 5, '281065a3b8142209f91240f27f32ed25', '94f433b3774d4e3eef87c213a4fe35cd'),
(28, 'TÃªnis Adidas Breaknet', 'branco', '42', 199.00, 9, 1, 4, '179e876dc3c1f13f619dc14328b4880d', '64d5584f0f5b45be647dbdcaf970397a'),
(29, 'Homem Adidas TÃªnis Hoops', 'branco', '42', 599.00, 9, 1, 3, 'db214d45684db4a078d59c3dd84c7925', 'c7cddf5dda72567334a6b2f4759e8b7e'),
(30, 'Adidas Chuteiras De Futebol X Crazyfast League', 'amarelo', '42', 639.00, 9, 4, 4, 'c84b351ab499b408915491e16a42d8a6', '1756e8747b1fc29ee4636540ec18b50d'),
(32, 'TÃªnis Tesla Hertz Art', 'preto', '42', 399.00, 13, 1, 4, '4a166b634015299bb96e15f6140fadae', 'dab45d558fb7079786bd2b0fa9e209b4'),
(33, 'TÃªnis Tesla Fusion Black', 'preto e branco', '42', 399.00, 13, 1, 4, 'a54ba01841e528e1e6b99eacd72a5dec', '3879df399fff55da1444bcccf28001e8'),
(34, 'TÃªnis Masculino New Balance', 'preto e branco', '42', 200.00, 14, 1, 4, 'cfe3a007e170940d0cac8854c787e6f5', 'd1013340a7cf63a43514f0ca4e6f2c90');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo`
--

CREATE TABLE IF NOT EXISTS `tipo` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `tipo`
--

INSERT INTO `tipo` (`codigo`, `nome`) VALUES
(3, 'cano alto'),
(4, 'cano baixo'),
(5, 'chuteira');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `login` varchar(10) NOT NULL,
  `senha` varchar(10) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`codigo`, `login`, `senha`) VALUES
(2, 'quinhos', '1234'),
(3, 'cris', '1234'),
(4, 'heitos', '3443'),
(5, 'jow', '926@#_'),
(6, 'doni', '3120'),
(7, 'teste', '1234'),
(8, 'teste', '1234');

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`codmarca`) REFERENCES `marca` (`codigo`),
  ADD CONSTRAINT `produto_ibfk_2` FOREIGN KEY (`codcategoria`) REFERENCES `categoria` (`codigo`),
  ADD CONSTRAINT `produto_ibfk_3` FOREIGN KEY (`codtipo`) REFERENCES `tipo` (`codigo`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
