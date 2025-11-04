-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 03-Nov-2025 às 17:16
-- Versão do servidor: 5.7.17
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_perifericos`
--
CREATE DATABASE IF NOT EXISTS `ecommerce_perifericos` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ecommerce_perifericos`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `administrador`
--

CREATE TABLE `administrador` (
  `codigo` int(5) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `nivel_acesso` tinyint(1) NOT NULL DEFAULT '1',
  `criado_por` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `administrador`
--

INSERT INTO `administrador` (`codigo`, `nome`, `email`, `senha`, `nivel_acesso`, `criado_por`) VALUES
(1, 'Matheus Donadel Marques', 'matheusteste@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 2, NULL),
(2, 'Marcus V', 'marcusteste@gmail.com', 'fd3859abaea98781d418dfbb62674795', 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `codigo` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(5) NOT NULL,
  `atualizado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `criado_em` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `carrinho`
--

INSERT INTO `carrinho` (`codigo`, `usuario_id`, `atualizado_em`, `criado_em`) VALUES
(1, 2, '2025-09-29 19:08:35', '2025-09-29 21:08:35');

-- --------------------------------------------------------

--
-- Estrutura da tabela `carrinho_item`
--

CREATE TABLE `carrinho_item` (
  `codigo` int(10) UNSIGNED NOT NULL,
  `carrinho_id` int(10) UNSIGNED NOT NULL,
  `produto_id` int(5) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` float(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `codigo` int(5) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`codigo`, `nome`, `descricao`) VALUES
(1, 'Mouse', ''),
(2, 'Teclado', ''),
(3, 'Headset', ''),
(4, 'Cadeira Gamer', ''),
(5, 'Mousepad', ''),
(6, 'Microfone', ''),
(7, 'Monitor', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `marca`
--

CREATE TABLE `marca` (
  `codigo` int(5) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `pais` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `marca`
--

INSERT INTO `marca` (`codigo`, `nome`, `pais`) VALUES
(1, 'Razer', 'Estados Unidos'),
(2, 'Logitech', 'SuiÃ§a'),
(3, 'Corsair', 'Estados Unidos'),
(4, 'SteelSeries', 'Dinamarca'),
(5, 'HyperX', 'Estados Unidos'),
(6, 'Redragon', 'China'),
(7, 'ASUS ROG', 'Taiwan'),
(8, 'Cooler Master', 'Taiwan'),
(9, 'Sennheiser', 'Alemanha'),
(10, 'Elgato', 'Alemanha'),
(11, 'RODE', 'Austrália'),
(12, 'BenQ', 'Taiwan');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedido`
--

CREATE TABLE `pedido` (
  `codigo` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `data_pedido` datetime NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'aguardando_pagamento',
  `forma_pagamento` varchar(50) NOT NULL,
  `stripe_session_id` varchar(255) DEFAULT NULL,
  `criado_em` datetime NOT NULL,
  `atualizado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `pedido`
--

INSERT INTO `pedido` (`codigo`, `cliente_id`, `data_pedido`, `total`, `status`, `forma_pagamento`, `stripe_session_id`, `criado_em`, `atualizado_em`) VALUES
(7, 2, '2025-10-29 04:55:31', '5999.80', 'pago', 'abacatepay', 'bill_AySNZqDbLt0nf2JSEJMkqsfk', '2025-10-29 04:55:31', '2025-10-29 00:56:07'),
(8, 2, '2025-10-31 23:33:42', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:33:42', NULL),
(9, 2, '2025-10-31 23:38:09', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:38:09', NULL),
(10, 2, '2025-10-31 23:38:24', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:38:24', NULL),
(11, 2, '2025-10-31 23:42:35', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:42:35', NULL),
(12, 2, '2025-10-31 23:42:43', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:42:43', NULL),
(13, 2, '2025-10-31 23:43:27', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:43:27', NULL),
(14, 2, '2025-10-31 23:43:41', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:43:41', NULL),
(15, 2, '2025-10-31 23:44:54', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:44:54', NULL),
(16, 2, '2025-10-31 23:48:12', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:48:12', NULL),
(17, 2, '2025-10-31 23:49:28', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:49:28', NULL),
(18, 2, '2025-10-31 23:50:44', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:50:44', NULL),
(19, 2, '2025-10-31 23:50:52', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:50:52', NULL),
(20, 2, '2025-10-31 23:54:00', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:54:00', NULL),
(21, 2, '2025-10-31 23:54:43', '1099.80', 'aguardando_pagamento', 'abacatepay', NULL, '2025-10-31 23:54:43', NULL),
(22, 2, '2025-10-31 23:56:20', '1099.80', 'pago', 'abacatepay', 'bill_gLamgXLRYZTnm4GsGuWrmhXn', '2025-10-31 23:56:20', '2025-10-31 22:56:40'),
(23, 2, '2025-10-31 23:59:44', '150.99', 'pago', 'abacatepay', 'bill_6ELwYPQKWHyALeWTuyFrB2Y5', '2025-10-31 23:59:44', '2025-10-31 22:59:59'),
(24, 2, '2025-11-01 00:27:11', '1139.70', 'pago', 'abacatepay', 'bill_CGTqEtkp4aMqeFmXdpBFCyPN', '2025-11-01 00:27:11', '2025-10-31 23:27:42'),
(25, 2, '2025-11-01 01:08:15', '9999.90', 'pago', 'abacatepay', 'bill_xwG4HRjkA2LyqM1QLzEwLjfE', '2025-11-01 01:08:15', '2025-11-01 00:08:27'),
(26, 2, '2025-11-02 01:25:47', '1099.80', 'pago', 'abacatepay', 'bill_e6r0334JqQttB5qMs3ZdYLTE', '2025-11-02 01:25:47', '2025-11-02 00:26:03');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedido_item`
--

CREATE TABLE `pedido_item` (
  `codigo` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `pedido_item`
--

INSERT INTO `pedido_item` (`codigo`, `pedido_id`, `produto_id`, `quantidade`, `preco_unitario`, `subtotal`) VALUES
(1, 1, 27, 2, '2999.90', '5999.80'),
(2, 2, 27, 2, '2999.90', '5999.80'),
(3, 3, 27, 2, '2999.90', '5999.80'),
(4, 4, 27, 2, '2999.90', '5999.80'),
(5, 5, 27, 2, '2999.90', '5999.80'),
(6, 6, 27, 2, '2999.90', '5999.80'),
(7, 7, 27, 2, '2999.90', '5999.80'),
(8, 8, 7, 2, '549.90', '1099.80'),
(9, 9, 7, 2, '549.90', '1099.80'),
(10, 10, 7, 2, '549.90', '1099.80'),
(11, 11, 7, 2, '549.90', '1099.80'),
(12, 12, 7, 2, '549.90', '1099.80'),
(13, 13, 7, 2, '549.90', '1099.80'),
(14, 14, 7, 2, '549.90', '1099.80'),
(15, 15, 7, 2, '549.90', '1099.80'),
(16, 16, 7, 2, '549.90', '1099.80'),
(17, 17, 7, 2, '549.90', '1099.80'),
(18, 18, 7, 2, '549.90', '1099.80'),
(19, 19, 7, 2, '549.90', '1099.80'),
(20, 20, 7, 2, '549.90', '1099.80'),
(21, 21, 7, 2, '549.90', '1099.80'),
(22, 22, 7, 2, '549.90', '1099.80'),
(23, 23, 1, 1, '150.99', '150.99'),
(24, 24, 6, 3, '379.90', '1139.70'),
(25, 25, 30, 1, '9999.90', '9999.90'),
(26, 26, 7, 2, '549.90', '1099.80');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE `produto` (
  `codigo` int(5) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `cor` varchar(30) NOT NULL,
  `codmarca` int(5) NOT NULL,
  `codcategoria` int(5) NOT NULL,
  `descricao` text NOT NULL,
  `especificacoes` text NOT NULL,
  `preco` float(8,2) NOT NULL,
  `estoque` int(6) NOT NULL DEFAULT '0',
  `estoque_minimo` int(6) NOT NULL DEFAULT '5',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `foto1` varchar(100) NOT NULL,
  `foto2` varchar(100) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `produto`
--

INSERT INTO `produto` (`codigo`, `nome`, `modelo`, `cor`, `codmarca`, `codcategoria`, `descricao`, `especificacoes`, `preco`, `estoque`, `estoque_minimo`, `ativo`, `foto1`, `foto2`, `data_cadastro`, `data_atualizacao`) VALUES
(1, 'Mouse Gamer Sem Fio Logitech G PRO X SUPERLIGHT 2 - Rosa MagentaMouse Gamer Sem Fio Logitech G PRO X SUPERLIGHT 2 - Rosa Magenta', 'LOGITECH G PRO X SUPERLIGHT 2', 'Rosa-Magenta', 2, 1, '123', 'qdad', 150.99, 7, 4, 1, '0809ad08d9e2a815e5299d213a3b4e47.png', '7da35bdb16a84142ce8bafbfc3ccd28e.png', '2025-09-29 18:30:16', '2025-09-29 16:30:16'),
(2, 'Teclado MecÃ¢nico Razer Huntsman', 'Huntsman', 'Preto RGB', 1, 2, 'Teclado MecÃ¢nico Gamer Razer Huntsman Mini-Click Optical Switch Purple, Preto', '\r\nMarca	â€ŽRazer\r\nFabricante	â€ŽRazer\r\nSÃ©rie	â€ŽHuntsman\r\nCertificaÃ§Ã£o	â€ŽNÃ£o aplicÃ¡vel\r\nCor	â€ŽPreto\r\nAltura do produto	â€Ž1,45 polegadas\r\nLargura do produto	â€Ž4,07 polegadas\r\nTecnologia de conexÃ£o	â€ŽUSB-C\r\nQuantidade de botÃµes	â€Ž61\r\nFonte de alimentaÃ§Ã£o	â€ŽNÃ£o aplicÃ¡vel\r\nPlataforma de hardware	â€ŽPC\r\nSistema operacional	â€ŽWindows\r\nPilhas ou baterias inclusas	â€ŽNÃ£o\r\nNÃºmero de unidades	â€Ž1\r\nMaterial	â€ŽAlumÃ­nio Polibutileno tereftalato\r\nPeso do produto	â€Ž499 g\r\nDimensÃµes do produto	â€Ž29,36 x 10,34 x 3,68 cm; 498,95 g\r\nNÃºmero do modelo	â€ŽRZ03-03390500-R3U1\r\nFunciona a bateria ou pilha?	â€ŽNÃ£o\r\nEAN	â€Ž0811659038753, 8886419346197', 939.99, 6, 3, 1, '5a0553765c187d5c9d24ec7089986320.jpg', '379153d130e48957b89e804a0429184b.jpg', '2025-09-29 21:18:24', '2025-09-29 19:18:24'),
(3, 'Mouse Gamer Razer DeathAdder V2', 'DeathAdder V2', 'Preto', 1, 1, 'Mouse gamer com sensor óptico Focus+ 20K', 'Sensor 20.000 DPI, switches ópticos', 349.90, 20, 5, 1, '639d47d839cb4e4f79a0e70bb70b9e6e.jpg', '28a02f0ab0537877beeb5331ebc37dc8.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(4, 'Mouse Gamer Logitech G502 HERO', 'G502 HERO', 'Preto', 2, 1, 'Mouse gamer com 11 botões programáveis', 'Sensor HERO 25K, pesos ajustáveis', 299.90, 15, 5, 1, '0c5d8fe9fc2e6bd4ae260063c2d71552.jpg', 'b4c58ce194ca848a498d41c6de76e411.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(5, 'Mouse Gamer Corsair Harpoon RGB Wireless', 'Harpoon Wireless', 'Preto', 3, 1, 'Mouse sem fio leve para jogos', 'Conexão Slipstream Wireless, 60g', 229.90, 10, 5, 1, '5bba0457b2b84e6b413aef33cd881493.jpg', 'a995d87e41674b5ab29c80914808079f.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(6, 'Mouse Gamer SteelSeries Rival 600', 'Rival 600', 'Preto', 4, 1, 'Mouse com sistema de pesos e duplo sensor', 'TrueMove3+, 12.000 CPI', 379.90, 9, 5, 1, '17c6db118251c1dcafae36dbfb4fd6a7.png', '99d7ff7b668561198e32899065aa6727.png', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(7, 'Teclado Mecânico HyperX Alloy Origins', 'Alloy Origins', 'Preto RGB', 5, 2, 'Teclado gamer com switches HyperX', 'Switch HyperX Red, corpo em alumínio', 549.90, 14, 5, 1, 'c58c59c83f70e7169a04efacf0a731fb.jpg', 'f9ab378f4e750e8a5311d7a6a0340b0b.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(8, 'Teclado Mecânico Logitech G915 TKL Wireless', 'G915 TKL', 'Branco', 2, 2, 'Teclado gamer sem fio de baixo perfil', 'Switch GL, conexão Lightspeed', 1299.90, 8, 3, 1, 'e325a37f566abb0201f18d6d59682291.jpg', 'edfc13e57b19c2bfc594acc8f689022c.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(9, 'Teclado Mecânico Razer BlackWidow V3', 'BlackWidow V3', 'Preto', 1, 2, 'Teclado com switches verdes Razer', 'Switch Razer Green, RGB Chroma', 699.90, 14, 4, 1, '2882fe388f762d44d96d498df41f2b83.jpg', 'fe74f8d67d7b02535c8ffa7b8b3b7815.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(10, 'Teclado Mecânico Redragon Kumara', 'K552 Kumara', 'Preto', 6, 2, 'Teclado compacto mecânico', 'Switch Outemu Blue, iluminação vermelha', 199.90, 25, 6, 1, 'c26cc464d4352f0ec01cde32c272a614.jpeg', 'bc78ee0f183abcb74009485196dfe380.jpeg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(11, 'Headset Gamer HyperX Cloud II', 'Cloud II', 'Preto/Vermelho', 5, 3, 'Headset com som surround 7.1', 'Drivers 53mm, almofadas de memória', 499.90, 20, 5, 1, '74aaf7aa52886cf3211c390f6e97d473.jpg', '676fd03ddfbfa5715ad207fa5777039f.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(12, 'Headset Gamer SteelSeries Arctis 7', 'Arctis 7', 'Branco', 4, 3, 'Headset sem fio com baixa latência', 'Som DTS Headphone:X v2.0', 1699.99, 10, 5, 1, 'c0ff78d3efc6117919873dfabd21fc80.jpg', '33c212acc2a6f0866baaa13df2fd2f27.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(13, 'Headset Gamer Logitech G733', 'G733', 'Preto', 2, 3, 'Headset sem fio com iluminação RGB', 'Lightspeed Wireless, microfone removível', 749.90, 9, 4, 1, 'a9f8e745ab016605a858d5fe4793e538.jpg', 'b45be7870fa35b04dc1d3d8b093ee54b.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(14, 'Headset Gamer Razer Kraken X', 'Kraken X', 'Preto', 1, 3, 'Headset leve com som 7.1', 'Peso 250g, drivers de 40mm', 299.90, 16, 5, 1, '013564cf92b0ea19ce00d0dfb1ccdbaa.jpg', 'a7a3ed54fb3d9598ae0e9356d21ab76a.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(15, 'Cadeira Gamer Cougar Armor One', 'Armor One', 'Dourada', 8, 4, 'Cadeira gamer ergonômica', 'Reclinável até 180°, espuma moldada', 1299.90, 5, 2, 1, '1756bb5b461b2872828c0ea548902151.jpg', 'cbe8c0f48b6f05101d40ce60f9880440.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(16, 'Cadeira Gamer ThunderX3 TGC12', 'TGC12', 'Preta/Azul', 6, 4, 'Cadeira com apoio ajustável', 'Couro sintético, regulagem de altura', 999.90, 6, 2, 1, '32b17d366b433a00222b31492a1b912d.jpg', 'c3a08000e6d81502a6d6c898d070145d.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(17, 'Cadeira Gamer Iskur V2 X', 'Iskur', 'Verde/Preto', 1, 4, 'Cadeira com suporte lombar ajustável', 'Estofamento premium, apoio de braços 4D', 2999.90, 3, 1, 1, 'b7ec1af14b981e2de1d67f2c86a9596b.jpg', '8f245944a73f693ac16557e27ca7f44d.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(18, 'Cadeira Gamer Nexusgamer Scorpion 2', 'Scorpio', 'Preto', 6, 4, 'Cadeira gamer custo-benefício', 'Base reforçada, ajuste de inclinação', 999.99, 7, 3, 1, 'b458e1aef64691c4bab342bb61303396.jpg', '632f63271aee0847c3035c225f4fe92a.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(19, 'Mousepad Gamer Corsair MM300 Extended', 'MM300', 'Preto', 3, 5, 'Mousepad estendido em tecido', '900x300mm, costura reforçada', 179.90, 20, 5, 1, '9c16b9945682051706fe36c569908f31.jpg', '8ecef8ef60b9c2ffb22f91622b4b3c7c.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(20, 'Mousepad Gamer Razer Goliathus Chroma', 'Goliathus Chroma', 'Preto RGB', 1, 5, 'Mousepad RGB com iluminação Chroma', '300x250mm, cabo removível', 299.90, 15, 4, 1, '5d00b30669f3c187799d210dd3003b85.jpg', 'bb60edb135320020a4bb7c4643128cf7.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(21, 'Mousepad Gamer SteelSeries QcK+', 'QcK+', 'Preto', 4, 5, 'Mousepad em tecido de alta qualidade', '450x400mm, base antiderrapante', 119.90, 18, 5, 1, 'a5ddf51e5b9958266974b46edd52269a.jpg', '202d6068f29297afb7c4f510be8f15a9.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(22, 'Mousepad Gamer Logitech Powerplay', 'Powerplay', 'Preto', 2, 5, 'Mousepad com carregamento sem fio', 'Compatível com G502 e G903', 799.90, 6, 2, 1, 'e2edd8b56fe29a6b863104e0292bcdf6.jpg', '550f279611bc6d30cd94ae0a485d39c2.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(23, 'Microfone Gamer HyperX QuadCast', 'QuadCast', 'Preto/Vermelho', 5, 6, 'Microfone condensador para streaming', 'Filtro pop embutido, iluminação LED', 749.90, 12, 4, 1, '8fe8b5a76fdebb9dff07da923acf8b9f.jpg', '00ae0dba4386863c2eef449122455f4b.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(24, 'Microfone Gamer Blue Yeti X', 'Yeti X', 'Preto', 9, 6, 'Microfone USB premium', 'Padrões múltiplos de captação', 899.90, 10, 4, 1, '4116a358e7c17434bd691af2b09aff02.jpeg', 'fb044a050ffcebdd5792cc3bccb2a2f6.jpeg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(25, 'Microfone Gamer RODE NT-USB', 'NT-USB', 'Preto', 11, 6, 'Microfone condensador USB', 'Compatível com PC e Mac', 1199.90, 7, 3, 1, '299955de24fcebb4f96893694509b61d.jpg', 'a965dbef8370a3a522289412494f3301.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(26, 'Microfone Gamer Elgato Wave 3', 'Wave 3', 'Preto', 10, 6, 'Microfone USB com software avançado', 'Wave Link, mute capacitivo', 999.90, 8, 3, 1, '3080a05235386ced9bfac8091c0af14d.jpeg', '75a3107860bfb6863f09c9bd7c8cbf13.jpeg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(27, 'Monitor Gamer ASUS ROG Swift 27\"', 'ROG Swift PG279Q', 'Preto', 7, 7, 'Monitor 27\" IPS com 165Hz', 'Resolução 2560x1440, G-Sync', 2999.90, 4, 2, 1, 'f1b5d5e9b8fd255a75ca5a3fa1d8dc82.jpeg', '2211147bc276c91ab5f4226fdfa73a26.jpeg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(28, 'Monitor Gamer BenQ Zowie XL2546', 'XL2546', 'Preto', 12, 7, 'Monitor 240Hz para eSports', '24.5\", DyAc+, tempo resposta 0.5ms', 2799.90, 5, 2, 1, '4d0546585a8c99c1b36c607e26b2e15e.jpg', 'bdd77e03c79ec1d05b7c73b87cb2b984.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(29, 'Monitor Gamer LG UltraGear 34\"', '34GN850-B', 'Preto', 7, 7, 'Monitor ultrawide 34\" curvo', '144Hz, 1ms, G-Sync Compatible', 4599.90, 4, 1, 1, 'bbf0145e3506f34202750447969d765d.jpg', '66367f9037e9846e02d8999dc0c22e89.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48'),
(30, 'Monitor Gamer Samsung Odyssey G9', 'Odyssey G9', 'Branco', 7, 7, 'Monitor super ultrawide 49\"', '240Hz, HDR1000, curvatura 1000R', 9999.90, 1, 1, 1, '70914dfb6666d5633ece0e77db160413.jpg', '6bc3b3029b3b0851a0436060833fc42d.jpg', '2025-09-29 16:32:48', '2025-09-29 19:32:48');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `codigo` int(5) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `endereco` varchar(200) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `complemento` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) NOT NULL,
  `cidade` varchar(50) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `data_nascimento` date NOT NULL,
  `sexo` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`codigo`, `nome`, `email`, `senha`, `cpf`, `telefone`, `endereco`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `cep`, `data_nascimento`, `sexo`) VALUES
(2, 'quinhos', 'markinhuszanin@gmail.com', '7ca447442dc5cea8193883d0dc7114ba', '11261219910', '48996949417', 'casa do karalho', '69', 'dsfsd', 'centro', 'Sideropolis', 'SC', '88860000', '2007-11-24', 'M');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`codigo`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `nivel_acesso` (`nivel_acesso`),
  ADD KEY `criado_por` (`criado_por`);

--
-- Indexes for table `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`codigo`),
  ADD UNIQUE KEY `usuario_unico` (`usuario_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `carrinho_item`
--
ALTER TABLE `carrinho_item`
  ADD PRIMARY KEY (`codigo`),
  ADD UNIQUE KEY `unique_item` (`carrinho_id`,`produto_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`codigo`);

--
-- Indexes for table `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`codigo`);

--
-- Indexes for table `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idx_cliente_id` (`cliente_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_stripe_session` (`stripe_session_id`);

--
-- Indexes for table `pedido_item`
--
ALTER TABLE `pedido_item`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `idx_pedido_id` (`pedido_id`),
  ADD KEY `idx_produto_id` (`produto_id`);

--
-- Indexes for table `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `codmarca` (`codmarca`),
  ADD KEY `codcategoria` (`codcategoria`),
  ADD KEY `ativo` (`ativo`),
  ADD KEY `estoque` (`estoque`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`codigo`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrador`
--
ALTER TABLE `administrador`
  MODIFY `codigo` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `codigo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `carrinho_item`
--
ALTER TABLE `carrinho_item`
  MODIFY `codigo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `categoria`
--
ALTER TABLE `categoria`
  MODIFY `codigo` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `marca`
--
ALTER TABLE `marca`
  MODIFY `codigo` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `pedido`
--
ALTER TABLE `pedido`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `pedido_item`
--
ALTER TABLE `pedido_item`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `produto`
--
ALTER TABLE `produto`
  MODIFY `codigo` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `codigo` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`criado_por`) REFERENCES `administrador` (`codigo`);

--
-- Limitadores para a tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `fk_carrinho_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`codigo`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `carrinho_item`
--
ALTER TABLE `carrinho_item`
  ADD CONSTRAINT `fk_item_carrinho` FOREIGN KEY (`carrinho_id`) REFERENCES `carrinho` (`codigo`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_item_produto` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`codigo`);

--
-- Limitadores para a tabela `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_usuario` FOREIGN KEY (`cliente_id`) REFERENCES `usuario` (`codigo`) ON DELETE NO ACTION;

--
-- Limitadores para a tabela `pedido_item`
--
ALTER TABLE `pedido_item`
  ADD CONSTRAINT `fk_pedido_item_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedido` (`codigo`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pedido_item_produto` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`codigo`) ON DELETE NO ACTION;

--
-- Limitadores para a tabela `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`codmarca`) REFERENCES `marca` (`codigo`),
  ADD CONSTRAINT `produto_ibfk_2` FOREIGN KEY (`codcategoria`) REFERENCES `categoria` (`codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
