-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2022 at 05:20 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pura_vida_indigena`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrador`
--

CREATE TABLE `administrador` (
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `contacto` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `artesanos`
--

CREATE TABLE `artesanos` (
  `codigo_artesano` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `contacto` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `artesanosxproducto`
--

CREATE TABLE `artesanosxproducto` (
  `codigo_artesano` int(11) NOT NULL,
  `codigo_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `celular` int(20) NOT NULL,
  `usuario` varchar(12) NOT NULL,
  `email` varchar(30) NOT NULL,
  `contraseña` varchar(30) NOT NULL,
  `cedula` int(20) NOT NULL,
  `direccion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `direccion`
--

CREATE TABLE `direccion` (
  `direccion` varchar(100) NOT NULL,
  `id_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `empresa_logistica`
--

CREATE TABLE `empresa_logistica` (
  `id_empresa` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `contacto_empresa` varchar(20) NOT NULL,
  `direccion_cliente` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `empresa_logisticaxpedidos`
--

CREATE TABLE `empresa_logisticaxpedidos` (
  `id_empresa` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `fecha_pedido` date NOT NULL,
  `id_empresa_logistica` int(11) NOT NULL,
  `fecha_entrega` date NOT NULL,
  `direccion_envio` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `codigo` int(11) NOT NULL,
  `imagen` varchar(45) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `productoxpedido`
--

CREATE TABLE `productoxpedido` (
  `codigo_producto` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tipo_producto`
--

CREATE TABLE `tipo_producto` (
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `codigo_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `usuario` text COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `foto` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  `ultimo_login` datetime NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `perfil`, `foto`, `estado`, `ultimo_login`, `fecha`) VALUES
(1, 'Usuario Administrador', 'admin', 'admin123', 'Administrador', '', 1, '0000-00-00 00:00:00', '2017-12-19 20:20:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artesanos`
--
ALTER TABLE `artesanos`
  ADD PRIMARY KEY (`codigo_artesano`);

--
-- Indexes for table `artesanosxproducto`
--
ALTER TABLE `artesanosxproducto`
  ADD KEY `codigo_artesano` (`codigo_artesano`),
  ADD KEY `codigo_producto` (`codigo_producto`);

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indexes for table `direccion`
--
ALTER TABLE `direccion`
  ADD PRIMARY KEY (`direccion`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indexes for table `empresa_logistica`
--
ALTER TABLE `empresa_logistica`
  ADD PRIMARY KEY (`id_empresa`),
  ADD KEY `direccion_cliente` (`direccion_cliente`);

--
-- Indexes for table `empresa_logisticaxpedidos`
--
ALTER TABLE `empresa_logisticaxpedidos`
  ADD KEY `id_empresa` (`id_empresa`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `direccion_envio` (`direccion_envio`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`codigo`);

--
-- Indexes for table `productoxpedido`
--
ALTER TABLE `productoxpedido`
  ADD KEY `codigo_producto` (`codigo_producto`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indexes for table `tipo_producto`
--
ALTER TABLE `tipo_producto`
  ADD KEY `codigo_producto` (`codigo_producto`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artesanosxproducto`
--
ALTER TABLE `artesanosxproducto`
  ADD CONSTRAINT `artesanosxproducto_ibfk_1` FOREIGN KEY (`codigo_artesano`) REFERENCES `artesanos` (`codigo_artesano`),
  ADD CONSTRAINT `artesanosxproducto_ibfk_2` FOREIGN KEY (`codigo_producto`) REFERENCES `productos` (`codigo`);

--
-- Constraints for table `direccion`
--
ALTER TABLE `direccion`
  ADD CONSTRAINT `direccion_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Constraints for table `empresa_logistica`
--
ALTER TABLE `empresa_logistica`
  ADD CONSTRAINT `empresa_logistica_ibfk_1` FOREIGN KEY (`direccion_cliente`) REFERENCES `direccion` (`direccion`);

--
-- Constraints for table `empresa_logisticaxpedidos`
--
ALTER TABLE `empresa_logisticaxpedidos`
  ADD CONSTRAINT `empresa_logisticaxpedidos_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `empresa_logistica` (`id_empresa`),
  ADD CONSTRAINT `empresa_logisticaxpedidos_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`);

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`direccion_envio`) REFERENCES `direccion` (`direccion`);

--
-- Constraints for table `productoxpedido`
--
ALTER TABLE `productoxpedido`
  ADD CONSTRAINT `productoxpedido_ibfk_1` FOREIGN KEY (`codigo_producto`) REFERENCES `productos` (`codigo`),
  ADD CONSTRAINT `productoxpedido_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`);

--
-- Constraints for table `tipo_producto`
--
ALTER TABLE `tipo_producto`
  ADD CONSTRAINT `tipo_producto_ibfk_1` FOREIGN KEY (`codigo_producto`) REFERENCES `productos` (`codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
