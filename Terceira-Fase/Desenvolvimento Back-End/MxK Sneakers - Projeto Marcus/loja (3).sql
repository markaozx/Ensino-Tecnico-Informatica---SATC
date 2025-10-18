-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tempo de Geração: 14/04/2025 às 18h51min
-- Versão do Servidor: 5.5.20
-- Versão do PHP: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `loja`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`codigo`, `nome`) VALUES
(1, 'casual'),
(4, 'esportivo'),
(5, 'caminhada');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`codigo`, `login`, `senha`) VALUES
(2, 'quinhos', '1234'),
(3, 'cris', '1234'),
(4, 'heitos', '3443'),
(5, 'jow', '926@#_'),
(6, 'doni', '3120');

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
