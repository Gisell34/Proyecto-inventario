-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-09-2024 a las 21:26:02
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventariolicorera`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `Codigo` int(11) NOT NULL,
  `Nombres` varchar(150) NOT NULL,
  `Cargo` varchar(100) NOT NULL,
  `Correo_electronico` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `CodigoCliente` int(11) NOT NULL,
  `Nombres` varchar(150) NOT NULL,
  `Apellidos` text NOT NULL,
  `Cedula` int(50) NOT NULL,
  `Direccion` varchar(150) NOT NULL,
  `Ciudad` text NOT NULL,
  `Telefono` int(100) NOT NULL,
  `Correo_electronico` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`CodigoCliente`, `Nombres`, `Apellidos`, `Cedula`, `Direccion`, `Ciudad`, `Telefono`, `Correo_electronico`) VALUES
(2, 'freddy andres', 'mejia malaver', 1034678432, 'cll 34 b sur # 68 85', 'bogota', 5643212, 'ngiutr_12@gmail.com'),
(3, 'ivonne', 'navarrete', 1023865396, 'diag 59 # 11 - 32', 'bogota', 2147483647, 'colmsas@producto.com'),
(18, 'rubi', 'preciado', 2147483647, 'cra 69 # 56 - 20', 'cauca', 65489754, 'gdfsysh@hjgays.com'),
(20, 'cristian', 'patino', 36452947, 'cra 3 # 12 -24', 'bogota', 7653487, 'jsgsfstsgs@jjhsh.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL,
  `Id_usuario` int(11) DEFAULT NULL,
  `fecha_compra` date DEFAULT NULL,
  `Nombre_producto` varchar(100) NOT NULL,
  `cantidad` int(50) NOT NULL,
  `precio` int(50) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id_compra`, `Id_usuario`, `fecha_compra`, `Nombre_producto`, `cantidad`, `precio`, `total`) VALUES
(1, 2, '2024-08-29', '', 0, 0, 0.00),
(2, 2, '2024-08-30', '', 0, 0, 34840.00),
(3, NULL, '2024-08-31', '', 0, 0, 0.00),
(4, NULL, '2024-08-31', '', 0, 0, 0.00),
(5, NULL, '2024-09-01', '', 0, 0, 0.00),
(6, NULL, '2024-09-01', '', 0, 0, 0.00),
(7, NULL, '2024-09-01', '', 0, 0, 30200.00),
(8, NULL, '2024-09-01', '', 0, 0, 0.00),
(9, NULL, '0000-00-00', '', 1, 4160, 0.00),
(10, NULL, '0000-00-00', '', 1, 4030, 0.00),
(11, NULL, '0000-00-00', '', 1, 3380, 0.00),
(12, NULL, '0000-00-00', '', 1, 4394, 0.00),
(13, NULL, '0000-00-00', '', 1, 5800, 0.00),
(14, NULL, '0000-00-00', '', 1, 0, 0.00),
(15, NULL, '0000-00-00', '', 1, 0, 0.00),
(16, NULL, '0000-00-00', '', 1, 4394, 0.00),
(17, NULL, '0000-00-00', '', 1, 4394, 0.00),
(18, NULL, '0000-00-00', '', 1, 14000, 0.00),
(19, NULL, '0000-00-00', '', 1, 30200, 0.00),
(20, NULL, '0000-00-00', '', 1, 30200, 0.00),
(27, 3, '2024-09-10', '', 0, 0, 0.00),
(28, 3, '2024-09-10', '', 0, 0, 0.00),
(29, 3, '2024-09-10', '', 0, 0, 0.00),
(30, 3, '2024-09-10', '', 0, 0, 0.00),
(31, 3, '2024-09-10', '', 0, 0, 0.00),
(32, 3, '2024-09-10', '', 0, 0, 0.00),
(33, 3, '2024-09-10', '', 0, 0, 0.00),
(34, 3, '2024-09-10', '', 0, 0, 16484.00),
(35, 3, '2024-09-10', '', 0, 0, 0.00),
(36, 3, '2024-09-10', '', 0, 0, 0.00),
(37, 3, '2024-09-10', '', 0, 0, 0.00),
(38, 2, '2024-09-11', '', 0, 0, 0.00),
(39, 2, '2024-09-11', '', 0, 0, 0.00),
(40, 2, '2024-09-11', '', 0, 0, 0.00),
(41, 2, '2024-09-11', '', 0, 0, 0.00),
(42, 2, '2024-09-11', 'Desconocido', 1, 3250, 3250.00),
(43, 2, '2024-09-11', 'Desconocido', 1, 3250, 3250.00),
(44, 2, '2024-09-11', 'Desconocido', 1, 3250, 3250.00),
(45, 2, '2024-09-11', 'Desconocido', 1, 3250, 3250.00),
(46, 2, '2024-09-11', 'Desconocido', 1, 3250, 3250.00),
(47, 2, '2024-09-11', 'Desconocido', 1, 3250, 3250.00),
(48, 2, '2024-09-11', 'Desconocido', 1, 3250, 3250.00),
(49, 2, '2024-09-11', 'Desconocido', 1, 3250, 3250.00),
(50, 2, '2024-09-11', 'Desconocido', 1, 3250, 3250.00),
(51, 2, '2024-09-11', 'Desconocido', 1, 3250, 3250.00),
(52, 2, '2024-09-11', 'Desconocido', 1, 3250, 3250.00),
(53, 2, '2024-09-11', 'Desconocido', 1, 3250, 3250.00),
(54, 2, '2024-09-12', 'Desconocido', 1, 3250, 3250.00),
(55, 2, '2024-09-12', 'Desconocido', 1, 3250, 3250.00),
(56, 2, '2024-09-12', 'Desconocido', 1, 3250, 3250.00),
(57, 2, '2024-09-12', 'Desconocido', 1, 3250, 3250.00),
(58, 2, '2024-09-12', 'Desconocido', 1, 3250, 3250.00),
(59, 2, '2024-09-12', 'Desconocido', 1, 4160, 7410.00),
(60, 2, '2024-09-12', 'Desconocido', 1, 3250, 3250.00),
(61, 2, '2024-09-12', 'Desconocido', 1, 4160, 7410.00),
(62, 2, '2024-09-12', 'Desconocido', 1, 3250, 3250.00),
(63, 2, '2024-09-12', 'Desconocido', 1, 4160, 7410.00),
(64, 2, '2024-09-12', 'Desconocido', 1, 3250, 3250.00),
(65, 2, '2024-09-12', 'Desconocido', 1, 4160, 7410.00),
(66, 2, '2024-09-12', 'Desconocido', 1, 3250, 3250.00),
(67, 2, '2024-09-12', 'Desconocido', 1, 4160, 7410.00),
(68, 2, '2024-09-12', 'Desconocido', 1, 3250, 3250.00),
(69, 2, '2024-09-12', 'Desconocido', 1, 4160, 7410.00),
(70, 2, '2024-09-12', 'Desconocido', 1, 3250, 3250.00),
(71, 2, '2024-09-12', 'Desconocido', 1, 4160, 7410.00),
(72, 2, '2024-09-12', 'Desconocido', 1, 4160, 4160.00),
(73, 2, '2024-09-12', 'Desconocido', 1, 4030, 8190.00),
(74, 3, '2024-09-12', 'Desconocido', 1, 4394, 4394.00),
(75, 3, '2024-09-12', 'Desconocido', 1, 4420, 8814.00),
(76, 3, '2024-09-12', 'Desconocido', 1, 3250, 12064.00),
(77, 3, '2024-09-12', 'Desconocido', 1, 3380, 3380.00),
(78, 3, '2024-09-12', 'Desconocido', 1, 4160, 7540.00),
(79, 3, '2024-09-12', 'Desconocido', 1, 4394, 11934.00),
(80, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(81, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(82, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(83, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(84, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(85, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(86, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(87, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(88, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(89, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(90, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(91, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(92, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(93, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(94, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(95, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(96, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(97, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(98, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(99, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(100, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(101, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(102, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(103, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(104, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(105, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(106, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(107, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(108, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(109, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(110, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(111, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(112, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(113, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(114, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(115, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(116, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(117, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(118, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(119, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(120, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(121, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(122, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(123, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(124, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(125, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(126, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(127, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(128, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(129, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(130, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(131, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(132, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(133, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(134, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(135, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(136, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(137, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(138, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(139, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(140, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(141, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(142, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(143, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(144, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(145, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(146, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(147, 3, '2024-09-11', 'Coca cola', 1, 3250, 3250.00),
(148, 3, '2024-09-11', 'detergente fa', 1, 4160, 4160.00),
(149, 3, '2024-09-11', 'cafe aguila roja', 1, 4394, 4394.00),
(150, 3, '2024-09-11', 'gomas trululu', 1, 4420, 4420.00),
(151, 2, '2024-09-12', 'Coca cola', 1, 3250, 3250.00),
(152, 2, '2024-09-12', 'detergente fa', 1, 4160, 4160.00),
(153, 2, '2024-09-12', 'detergente fa', 1, 4160, 4160.00),
(154, 2, '2024-09-12', 'gomas trululu', 1, 4420, 4420.00),
(155, 2, '2024-09-12', 'Coca cola', 1, 3250, 3250.00),
(156, 2, '2024-09-12', 'Brawnie', 1, 4030, 4030.00),
(157, 2, '2024-09-12', 'gomas trululu', 1, 4420, 4420.00),
(158, 2, '2024-09-12', 'Coca cola', 1, 3250, 3250.00),
(159, 2, '2024-09-12', 'Brawnie', 1, 4030, 4030.00),
(160, 2, '2024-09-12', 'gomas trululu', 1, 4420, 4420.00),
(161, 2, '2024-09-12', 'Coca cola', 1, 3250, 3250.00),
(162, 2, '2024-09-12', 'Brawnie', 1, 4030, 4030.00),
(163, 2, '2024-09-12', 'gomas trululu', 1, 4420, 4420.00),
(164, 2, '2024-09-12', 'Coca cola', 1, 3250, 3250.00),
(165, 2, '2024-09-12', 'Brawnie', 1, 4030, 4030.00),
(166, 2, '2024-09-12', 'gomas trululu', 1, 4420, 4420.00),
(167, 2, '2024-09-12', 'Coca cola', 1, 3250, 3250.00),
(168, 2, '2024-09-12', 'Brawnie', 1, 4030, 4030.00),
(169, 2, '2024-09-12', 'gomas trululu', 1, 4420, 4420.00),
(170, 2, '2024-09-12', 'Coca cola', 1, 3250, 3250.00),
(171, 2, '2024-09-12', 'Brawnie', 1, 4030, 4030.00),
(172, 2, '2024-09-12', 'gomas trululu', 1, 4420, 4420.00),
(173, 2, '2024-09-12', 'cafe aguila roja', 1, 4394, 4394.00),
(174, 2, '2024-09-12', 'Brawnie', 1, 4030, 4030.00),
(175, 2, '2024-09-12', 'Coca cola', 1, 3250, 3250.00),
(176, 2, '2024-09-12', 'cafe aguila roja', 1, 4394, 4394.00),
(177, 2, '2024-09-12', 'Brawnie', 1, 4030, 4030.00),
(178, 2, '2024-09-12', 'Coca cola', 1, 3250, 3250.00),
(179, 2, '2024-09-12', 'cafe aguila roja', 1, 4394, 4394.00),
(180, 2, '2024-09-12', 'Brawnie', 1, 4030, 4030.00),
(181, 2, '2024-09-12', 'Coca cola', 1, 3250, 3250.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_compras`
--

CREATE TABLE `detalles_compras` (
  `id_detalle` int(11) NOT NULL,
  `id_compra` int(11) DEFAULT NULL,
  `Codigoproducto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `tipo_producto` enum('producto','servicio') NOT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_compras`
--

INSERT INTO `detalles_compras` (`id_detalle`, `id_compra`, `Codigoproducto`, `cantidad`, `tipo_producto`, `precio`) VALUES
(1, 9, 2, 1, 'producto', 3250.00),
(2, 9, 6, 1, 'producto', 4160.00),
(3, 10, 9, 1, 'producto', 4420.00),
(4, 10, 1, 1, 'producto', 4030.00),
(5, 11, 2, 1, 'producto', 3250.00),
(6, 11, 8, 1, 'producto', 3380.00),
(7, 12, 2, 1, 'producto', 3250.00),
(8, 12, 3, 1, 'producto', 4394.00),
(9, 13, 1, 1, 'producto', 4030.00),
(10, 13, 1, 1, 'producto', 0.00),
(11, 13, 2, 1, 'producto', 0.00),
(12, 16, 1, 1, 'producto', 4030.00),
(13, 16, 3, 1, 'producto', 4394.00),
(14, 17, 2, 1, 'producto', 3250.00),
(15, 17, 3, 1, 'producto', 4394.00),
(16, 18, 2, 1, 'producto', 3250.00),
(17, 18, 9, 1, 'producto', 4420.00),
(18, 18, 2, 1, 'producto', 0.00),
(19, 18, 6, 1, 'producto', 0.00),
(20, 19, 3, 1, 'producto', 4394.00),
(22, 20, 3, 1, 'producto', 4394.00),
(24, 34, 3, 1, 'producto', 4394.00),
(25, 34, 1, 3, 'producto', 4030.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `id_detalle_pedido` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `ID_Factura` int(11) NOT NULL,
  `Id_usuario` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `Nombre_cliente` varchar(255) NOT NULL,
  `Apellido_cliente` varchar(255) NOT NULL,
  `Productos` text NOT NULL,
  `Cantidad` int(50) NOT NULL,
  `Precio` int(50) NOT NULL,
  `Nombre_servicio` varchar(150) NOT NULL,
  `Tipo_servicio` varchar(150) NOT NULL,
  `Precio_venta` int(50) NOT NULL,
  `Total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`ID_Factura`, `Id_usuario`, `Fecha`, `Nombre_cliente`, `Apellido_cliente`, `Productos`, `Cantidad`, `Precio`, `Nombre_servicio`, `Tipo_servicio`, `Precio_venta`, `Total`) VALUES
(1, 2, '2024-08-29', 'freddy andres', 'mejia malaver', 'brawnie, cocacola', 0, 0, '', '', 0, 34840.00),
(2, 2, '2024-08-29', 'freddy andres', 'mejia malaver', 'brawnie, cocacola', 0, 0, '', '', 0, 34840.00),
(3, 2, '2024-09-06', 'freddy andres', 'mejia malaver', 'cerveza light, leche entera', 4, 10000, '', '', 0, 10000.00),
(7, 2, '2024-09-12', 'freddy andres', 'mejia malaver', '0', 2, 7410, '', '', 0, 7410.00),
(8, 2, '2024-09-12', 'freddy andres', 'mejia malaver', '0', 2, 8190, '', '', 0, 8190.00),
(9, 3, '2024-09-12', 'ivonne', 'navarrete', '0', 3, 12064, '', '', 0, 12064.00),
(10, 3, '2024-09-12', 'ivonne', 'navarrete', '0', 3, 11934, '', '', 0, 11934.00),
(11, 3, '2024-09-11', 'ivonne', 'navarrete', 'cafe aguila roja (1 x 4394.00), Coca cola (1 x 3250.00), detergente fa (1 x 4160.00)', 3, 11804, '', '', 0, 11804.00),
(12, 3, '2024-09-11', 'ivonne', 'navarrete', 'cafe aguila roja (1 x 4394.00), gomas trululu (1 x 4420.00)', 2, 8814, '', '', 0, 8814.00),
(13, 2, '2024-09-12', 'freddy andres', 'mejia malaver', 'Coca cola (1 x 3250.00)', 1, 3250, '', '', 0, 3250.00),
(14, 2, '2024-09-12', 'freddy andres', 'mejia malaver', 'detergente fa (1 x 4160.00), ', 1, 4160, '0', 'servicio', 5800, 9960.00),
(15, 2, '2024-09-12', 'freddy andres', 'mejia malaver', 'detergente fa (1 x 4160.00), gomas trululu (1 x 4420.00)', 2, 8580, '0', 'Domicilios', 12000, 20580.00),
(16, 2, '2024-09-12', 'freddy andres', 'mejia malaver', 'Coca cola (1 x 3250.00), Brawnie (1 x 4030.00), gomas trululu (1 x 4420.00), ', 3, 11700, '0', 'servicio', 5800, 17500.00),
(17, 2, '2024-09-12', 'freddy andres', 'mejia malaver', 'cafe aguila roja (1 x 4394.00), Brawnie (1 x 4030.00), Coca cola (1 x 3250.00)', 3, 11674, 'comidita a casa (Domicilios - 5800)', 'Domicilios', 5800, 17474.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ganancias_mensuales`
--

CREATE TABLE `ganancias_mensuales` (
  `id` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `valor_compras` decimal(10,2) DEFAULT 0.00,
  `valor_ventas` decimal(10,2) DEFAULT 0.00,
  `ganancia_neta` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_precios`
--

CREATE TABLE `historial_precios` (
  `id` int(11) NOT NULL,
  `Codigoproducto` int(11) NOT NULL,
  `Precio_anterior` decimal(10,2) NOT NULL,
  `Precio_actual` decimal(10,2) NOT NULL,
  `Fecha_cambio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `Codigoproducto` int(11) NOT NULL,
  `Cantidad_producto` int(50) NOT NULL,
  `Precio` int(100) NOT NULL,
  `Entrada` int(50) NOT NULL,
  `Salida` int(50) NOT NULL,
  `existencias_total` int(255) NOT NULL,
  `Stock_minimo` int(50) NOT NULL,
  `Stock_maximo` int(50) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `ganancia` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`Codigoproducto`, `Cantidad_producto`, `Precio`, `Entrada`, `Salida`, `existencias_total`, `Stock_minimo`, `Stock_maximo`, `precio_venta`, `ganancia`) VALUES
(1, 35, 3100, 35, 0, 0, 5, 39, 4030.00, 930),
(2, 75, 2500, 75, 0, 0, 9, 83, 3250.00, 750),
(3, 11, 3380, 11, 0, 11, 2, 13, 4394.00, 1014),
(6, 35, 3380, 35, 0, 35, 5, 39, 4160.00, 780),
(8, 25, 2600, 25, 0, 25, 3, 28, 3380.00, 780),
(9, 40, 3400, 40, 0, 40, 8, 12, 4420.00, 1020);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_pedido` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `Codigoproducto` int(11) NOT NULL,
  `Nombre_producto` varchar(150) NOT NULL,
  `Fabricante` varchar(100) NOT NULL,
  `Tipo_producto` varchar(100) NOT NULL,
  `Especificaciones` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`Codigoproducto`, `Nombre_producto`, `Fabricante`, `Tipo_producto`, `Especificaciones`) VALUES
(1, 'Brawnie', 'Maria', 'torta negra', 'Torta negra sabor chocolate, rellena de arequipe.'),
(2, 'Coca cola', 'Coca cola', 'bebida gacificada', 'bebida gacificada negra, 350 ml presentacion personal.'),
(3, 'cafe aguila roja', 'bavaria', 'bebida alcoholica', 'bebida alcoholica en botella'),
(6, 'detergente fa', 'p&h', 'detergente en polvo', 'detergente en polvo en bolsa de 350ml'),
(8, 'ariel', 'p&h', 'detergente en polvo', 'detergente en polvo en bolsa de 500 mg'),
(9, 'gomas trululu', 'italo', 'gomas de dulce', 'comas de dulces de sabores variados');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `Codigoproveedor` int(11) NOT NULL,
  `Codigoproducto` int(11) NOT NULL,
  `Nombre_proveedor` varchar(150) NOT NULL,
  `Nit` int(50) NOT NULL,
  `Cantidad_total` int(11) NOT NULL,
  `Direccion` varchar(150) NOT NULL,
  `Telefono` int(100) NOT NULL,
  `Correo_electronico` varchar(100) NOT NULL,
  `Correo_factura` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`Codigoproveedor`, `Codigoproducto`, `Nombre_proveedor`, `Nit`, `Cantidad_total`, `Direccion`, `Telefono`, `Correo_electronico`, `Correo_factura`) VALUES
(1, 1, 'Colombina', 134, 1, '0', 2147483647, 'colmsas@producto.com', 'colmsas@producto.com'),
(2, 2, 'malta', 2147483647, 1, '0', 2147483647, 'sdxjbhsxgibxw@kxnhoS.es', 'sdxjbhsxgibxw@kxnhoS.es');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `Codigo` int(11) NOT NULL,
  `Nombre_servicio` varchar(150) NOT NULL,
  `Tipo_servicio` varchar(150) NOT NULL,
  `Precio_venta` int(50) NOT NULL,
  `Id_usuario` int(11) DEFAULT NULL,
  `Estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`Codigo`, `Nombre_servicio`, `Tipo_servicio`, `Precio_venta`, `Id_usuario`, `Estado`) VALUES
(1, 'Trago hasta tu casa', 'Domicilios', 12000, 1, 'activo'),
(2, 'comidita a casa', 'Domicilios', 5800, 1, 'activo'),
(5, 'a casa seguro', 'transporte', 30200, 1, 'en espera'),
(6, 'brigada', 'ayuda', 14000, 1, '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id_solicitud` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha_solicitud` datetime DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `detalles` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Id_usuario` int(11) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Id_usuario`, `Usuario`, `Password`, `rol`) VALUES
(1, 'Gisell', '12345', 'Administrador'),
(2, 'freddy', '123456', 'Usuario'),
(3, 'ivonne', '$2y$10$AvP8HBsowR6LkrFc/DtU3ulcgn2M5yhgMB4oaidPan7', 'Usuario'),
(18, 'rubi', '$2y$10$F5CoDq7i3A5xpICtP3tKCu/.9DXDXf3d9xJTmZX8FZU', 'Usuario'),
(20, 'cristian', '$2y$10$Nft5mX.t97lNrz.iJYNKWuYrByCkKrXYtU28XCark9K', 'Usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`CodigoCliente`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `fk_cliente` (`Id_usuario`);

--
-- Indices de la tabla `detalles_compras`
--
ALTER TABLE `detalles_compras`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `Codigoproducto` (`Codigoproducto`) USING BTREE;

--
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id_detalle_pedido`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`ID_Factura`),
  ADD KEY `Id_usuario` (`Id_usuario`);

--
-- Indices de la tabla `ganancias_mensuales`
--
ALTER TABLE `ganancias_mensuales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mes` (`mes`,`anio`);

--
-- Indices de la tabla `historial_precios`
--
ALTER TABLE `historial_precios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Codigoproducto` (`Codigoproducto`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD UNIQUE KEY `Codigoproducto` (`Codigoproducto`) USING BTREE;

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`Codigoproducto`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`Codigoproveedor`),
  ADD UNIQUE KEY `Codigoproducto` (`Codigoproducto`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `Id_usuario` (`Id_usuario`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `CodigoCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT de la tabla `detalles_compras`
--
ALTER TABLE `detalles_compras`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id_detalle_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `ID_Factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `ganancias_mensuales`
--
ALTER TABLE `ganancias_mensuales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_precios`
--
ALTER TABLE `historial_precios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `Codigoproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `Codigoproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`CodigoCliente`) REFERENCES `usuarios` (`Id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuarios` (`Id_usuario`),
  ADD CONSTRAINT `fk_cliente` FOREIGN KEY (`Id_usuario`) REFERENCES `cliente` (`CodigoCliente`),
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`Id_usuario`) REFERENCES `cliente` (`CodigoCliente`);

--
-- Filtros para la tabla `detalles_compras`
--
ALTER TABLE `detalles_compras`
  ADD CONSTRAINT `detalles_compras_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`),
  ADD CONSTRAINT `detalles_compras_ibfk_2` FOREIGN KEY (`codigoproducto`) REFERENCES `producto` (`Codigoproducto`);

--
-- Filtros para la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD CONSTRAINT `detalle_pedidos_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `detalle_pedidos_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`Codigoproducto`);

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuarios` (`Id_usuario`),
  ADD CONSTRAINT `facturas_ibfk_2` FOREIGN KEY (`Id_usuario`) REFERENCES `usuarios` (`Id_usuario`);

--
-- Filtros para la tabla `historial_precios`
--
ALTER TABLE `historial_precios`
  ADD CONSTRAINT `historial_precios_ibfk_1` FOREIGN KEY (`Codigoproducto`) REFERENCES `producto` (`Codigoproducto`);

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `fk_inventario_producto` FOREIGN KEY (`Codigoproducto`) REFERENCES `producto` (`Codigoproducto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`Id_usuario`);

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`Codigoproducto`) REFERENCES `producto` (`Codigoproducto`);

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuarios` (`Id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
