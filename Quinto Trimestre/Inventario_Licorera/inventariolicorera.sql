-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-11-2024 a las 21:09:08
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

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
(3, 'ivonne', 'navarrete', 1023865396, 'diag 59 # 11 - 32', 'bogota', 2147483647, 'colmsas@producto.com'),
(25, 'nayibe', 'villa', 1034678432, 'diag 59 # 11 - 32', 'Bogota', 4567890, 'nayibe23@outlook.com'),
(26, 'Freddy', 'malaver', 1045768432, 'av cll 45 # 11 -89', 'bogota', 3457890, 'Freddy.mejia@outlook.es');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contactos`
--

INSERT INTO `contactos` (`id`, `nombre`, `correo`, `mensaje`, `fecha`) VALUES
(1, 'ivonne yadira navarrete', 'mnjdqcn@udne.com', 'deseo reservar varias mesas para el evento del dia viernes frente al escenario', '2024-11-20 02:59:46'),
(2, 'edison ospna', 'e.ospinac@cncnaos.com', 'njnkboknl', '2024-11-20 03:21:46'),
(3, 'ivonne yadira navarrete', 'vkhgjbjkbgk@knhljhnl.com', 'hkvghjbvgl', '2024-11-20 21:21:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `ID_Factura` int(11) NOT NULL,
  `Id_usuario` int(11) DEFAULT NULL,
  `Fecha` date NOT NULL,
  `Nombre_cliente` varchar(255) NOT NULL,
  `Apellido_cliente` varchar(255) NOT NULL,
  `Productos` text NOT NULL,
  `Cantidad` text NOT NULL,
  `Precio` text NOT NULL,
  `Nombre_servicio` varchar(150) DEFAULT NULL,
  `Tipo_servicio` varchar(150) DEFAULT NULL,
  `Precio_venta` int(50) DEFAULT NULL,
  `Total` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`ID_Factura`, `Id_usuario`, `Fecha`, `Nombre_cliente`, `Apellido_cliente`, `Productos`, `Cantidad`, `Precio`, `Nombre_servicio`, `Tipo_servicio`, `Precio_venta`, `Total`, `metodo_pago`) VALUES
(1, NULL, '2024-08-29', 'freddy andres', 'mejia malaver', 'brawnie, cocacola', '3', '30000', 'comidita a casa', 'domicilio', 5800, 90000.00, ''),
(2, NULL, '2024-08-29', 'freddy andres', 'mejia malaver', 'coca cola', '2', '7000', 'comida a casa', 'envio de comida', 4030, 11030.00, ''),
(3, NULL, '2024-09-06', 'freddy andres', 'mejia malaver', 'cerveza light, leche entera', '4', '10000', '', '', 0, 10000.00, ''),
(7, NULL, '2024-09-12', 'freddy andres', 'mejia malaver', '0', '2', '7410', 'envio de comida', '0', 14000, 2110.00, ''),
(8, NULL, '2024-09-12', 'freddy andres', 'mejia malaver', 'cerveza agula light', '2', '8190', '0', '0', 0, 16380.00, ''),
(9, 3, '2024-09-12', 'ivonne', 'navarrete', '0', '3', '12064', '', '', 0, 12064.00, ''),
(10, 3, '2024-09-12', 'ivonne', 'navarrete', '0', '3', '11934', '', '', 0, 11934.00, ''),
(11, 3, '2024-09-11', 'ivonne', 'navarrete', 'cafe aguila roja (1 x 4394.00), Coca cola (1 x 3250.00), detergente fa (1 x 4160.00)', '3', '11804', '', '', 0, 11804.00, ''),
(12, 3, '2024-09-11', 'ivonne', 'navarrete', 'cafe aguila roja (1 x 4394.00), gomas trululu (1 x 4420.00)', '2', '8814', '', '', 0, 8814.00, ''),
(13, NULL, '2024-09-12', 'freddy andres', 'mejia malaver', 'Coca cola (1 x 3250.00)', '1', '3250', '', '', 0, 3250.00, ''),
(14, NULL, '2024-09-12', 'freddy andres', 'mejia malaver', 'detergente fa (1 x 4160.00), ', '1', '4160', '0', 'servicio', 5800, 9960.00, ''),
(15, NULL, '2024-09-12', 'freddy andres', 'mejia malaver', 'detergente fa (1 x 4160.00), gomas trululu (1 x 4420.00)', '2', '8580', '0', 'Domicilios', 12000, 20580.00, ''),
(16, NULL, '2024-09-12', 'freddy andres', 'mejia malaver', 'Coca cola (1 x 3250.00), Brawnie (1 x 4030.00), gomas trululu (1 x 4420.00), ', '3', '11700', '0', 'servicio', 5800, 17500.00, ''),
(17, NULL, '2024-09-12', 'freddy andres', 'mejia malaver', 'cafe aguila roja (1 x 4394.00), Brawnie (1 x 4030.00), Coca cola (1 x 3250.00)', '3', '11674', 'comidita a casa (Domicilios - 5800)', 'Domicilios', 5800, 17474.00, ''),
(18, NULL, '2024-09-19', 'cristian', 'patino', 'cafe aguila roja (1 x 4394.00), ariel (1 x 3380.00)', '2', '7774', '', '', 0, 7774.00, ''),
(19, NULL, '2024-09-19', 'cristian', 'patino', 'gomas trululu (1 x 4420.00), Brawnie (1 x 4030.00)', '2', '8450', '', '', 0, 8450.00, ''),
(20, NULL, '2024-09-20', 'freddy andres', 'mejia malaver', 'detergente fa (1 x 4160.00), whiskey jhonny walker azul (1 x 230000.00)', '2', '234160', '', '', 0, 234160.00, ''),
(21, NULL, '2024-09-22', 'freddy andres', 'mejia malaver', 'cafe aguila roja (1 x 4394.00), Coca cola (1 x 3250.00), gomas trululu (1 x 4420.00), whiskey jhonny walker azul (1 x 230000.00)', '4', '242064', '', '', 0, 242064.00, ''),
(22, NULL, '2024-09-22', 'freddy andres', 'mejia malaver', 'Brawnie (1 x 4030.00), aguardiente amarillo (1 x 32000.00), Coca cola (1 x 3250.00)', '3', '39280', '', '', 0, 39280.00, ''),
(23, 25, '2024-11-12', 'nayibe', 'villa', 'cafe aguila roja (1 x 4394.00), aguardiente amarillo (1 x 35000.00)', '2', '39394', 'domicilio (entrega de compras virtuales - 15000)', 'entrega de compras virtuales', 15000, 54394.00, ''),
(24, NULL, '2024-11-12', 'freddy andres', 'mejia malaver', 'cafe aguila roja (1 x 4394.00), Brawnie (1 x 4030.00)', '2', '8424', '', '', 0, 8424.00, ''),
(25, NULL, '2024-11-12', 'freddy andres', 'mejia malaver', 'cafe aguila roja (1 x 4394.00), detergente fa (1 x 4160.00), Brawnie (1 x 4030.00)', '3', '12584', '', '', 0, 12584.00, ''),
(26, NULL, '2024-11-12', 'freddy andres', 'mejia malaver', '', '0', '0', '', '', 0, 0.00, ''),
(27, 26, '2024-11-16', 'Freddy', 'malaver', 'aguardiente amarillo, whiskey jhonny walker azul', '2, 1', '35000.00, 230000.00', 'compra boleteria', 'venta de boletas eventos especiales', 90000, 355000.00, 'Nequi'),
(28, 26, '2024-11-16', 'Freddy', 'malaver', 'aguardiente amarillo (1 x 35000.00), whiskey jhonny walker azul (1 x 230000.00), smirnoff manzana (1 x 9600.00), costeñita (1 x 3200.00)', '4', '277800', 'a beber en casa (envio de trago - 20000)', 'envio de trago', 20000, 297800.00, 'Efectivo'),
(29, 26, '2024-11-16', 'Freddy', 'malaver', 'Brawnie (1 x 4030.00), cafe aguila roja (1 x 4394.00), gomas trululu (1 x 4420.00)', '3', '12844', '', '', 0, 12844.00, 'Efectivo'),
(30, 26, '2024-11-16', 'Freddy', 'malaver', 'aguardiente amarillo (1 x 35000.00), whiskey jhonny walker azul (1 x 230000.00)', '2', '265000', '', '', 0, 265000.00, 'Daviplata'),
(31, 26, '2024-11-16', 'Freddy', 'malaver', 'cafe aguila roja (1 x 4394.00), Brawnie (1 x 4030.00)', '2', '8424', '', '', 0, 8424.00, 'Nequi'),
(32, 26, '2024-11-16', 'Freddy', 'malaver', 'Brawnie (1 x 4030.00), cafe aguila roja (1 x 4394.00)', '2', '8424', '', '', 0, 8424.00, 'Daviplata'),
(33, 26, '2024-11-16', 'Freddy', 'malaver', 'cafe aguila roja (1 x 4394.00)', '1', '4394', '', '', 0, 4394.00, 'Daviplata'),
(34, 26, '2024-11-17', 'Freddy', 'malaver', 'detergente fa (1 x 4160.00), Brawnie (1 x 4030.00)', '2', '8190', '', '0', 0, 8190.00, 'Daviplata'),
(35, 26, '2024-11-17', 'Freddy', 'malaver', '', '2', '39030', '', '', 0, 39030.00, 'Efectivo'),
(36, 26, '2024-11-17', 'Freddy', 'malaver', '', '2', '8554', '', '', 0, 8554.00, 'Tarjeta'),
(37, 26, '2024-11-17', 'Freddy', 'malaver', 'whiskey jhonny walker azul (1 x 230000.00)', '1', '230000', '', '0', 0, 230000.00, 'Daviplata'),
(38, 26, '2024-11-17', 'Freddy', 'malaver', 'Brawnie (1 x 4030.00), whiskey jhonny walker azul (1 x 230000.00)', '2', '234030', 'a beber en casa (envio de trago - 20000)', '0', 20000, 254030.00, 'Efectivo'),
(39, 26, '2024-11-17', 'Freddy', 'malaver', 'whiskey jhonny walker azul (1 x 230000.00), aguardiente amarillo (1 x 35000.00)', '2', '265000', 'domicilio (entrega de compras virtuales - 15000)', '0', 15000, 280000.00, 'Nequi'),
(40, 26, '2024-11-17', 'Freddy', 'malaver', 'whiskey jhonny walker azul (2 x 230000.00)', '2', '460000', 'domicilio (entrega de compras virtuales - 15000)', '0', 15000, 475000.00, 'Daviplata'),
(41, 26, '2024-11-17', 'Freddy', 'malaver', 'aguardiente amarillo (2 x 35000.00)', '2', '70000', 'compra boleteria (venta de boletas eventos especiales - 90000)', '0', 90000, 160000.00, 'Efectivo'),
(42, 26, '2024-11-17', 'Freddy', 'malaver', 'Brawnie (1 x 4030.00), gomas trululu (1 x 4420.00), Coca cola (1 x 3250.00)', '3', '11700', 'comidita a casa (envio de comida solicitada por la app - 14000)', '0', 14000, 25700.00, 'Tarjeta'),
(43, 26, '2024-11-17', 'Freddy', 'malaver', 'cafe aguila roja (1 x 4394.00), detergente fa (1 x 4160.00)', '2', '8554', 'domicil', '0', 15000, 23554.00, 'Efectivo'),
(44, 26, '2024-11-18', 'Freddy', 'malaver', 'aguardiente amarillo (1 x 35000.00)', '1', '35000', 'compra bol', '0', 90000, 125000.00, 'Daviplata'),
(45, 26, '2024-11-18', 'Freddy', 'malaver', 'ron santafe (1 x 40000.00), smirnoff manzana (1 x 9600.00), costeñita (1 x 3900.00)', '3', '53500', 'a beber en', '0', 20000, 73500.00, 'Nequi'),
(46, 26, '2024-11-19', 'Freddy', 'malaver', 'Brawnie, Coca cola, gomas trululu', '3', '4030', 'domicilio', '0', 15000, 26700.00, 'Nequi'),
(47, 26, '2024-11-20', 'Freddy', 'malaver', 'aguardiente amarillo, whiskey jhonny walker azul, cafe aguila roja', '3', '35000', 'domicilio', '0', 15000, 284394.00, 'Nequi'),
(48, 26, '2024-11-20', 'Freddy', 'malaver', 'Brawnie, gomas trululu, Coca cola', '3', '4030', '', '0', 0, 11700.00, 'Efectivo'),
(49, 26, '2024-11-21', 'Freddy', 'malaver', 'cafe aguila roja, detergente fa, Brawnie, Coca cola', '4', '4394', '', '0', 0, 15834.00, 'Efectivo'),
(50, 26, '2024-11-21', 'Freddy', 'malaver', 'Coca cola, Brawnie', '2', '3250', '', '0', 0, 7280.00, 'Efectivo'),
(51, 26, '2024-11-22', 'Freddy', 'malaver', 'aguardiente nectar verde, brandy san juan', '2', '139000', '', '0', 19000, 139000.00, 'Nequi'),
(52, 26, '2024-11-23', 'Freddy', 'malaver', 'aguardiente nectar verde, aguardiente amarillo', '2', '54000', '', '0', 19000, 54000.00, 'Efectivo'),
(53, 26, '2024-11-30', 'Freddy', 'malaver', 'Brawnie, Coca cola', '1', '4030.00, 3250.00', 'domicilio', '0', 0, 7280.00, 'Efectivo'),
(54, 26, '2024-11-30', 'Freddy', 'malaver', 'detergente fa, Coca cola, Brawnie, gomas trululu, aguardiente amarillo', '1, 1, 1, 1, 1', '4160.00, 3250.00, 4030.00, 4420.00, 35000.00', 'domicilio', '0', 0, 50860.00, 'Daviplata'),
(55, 26, '2024-12-01', 'Freddy', 'malaver', 'Brawnie, aguardiente amarillo, gomas trululu', '1, 1, 1', '4030.00, 35000.00, 4420.00', '0', 'entrega de compras virtuales', 0, 58450.00, 'Nequi'),
(56, 26, '2024-12-04', 'Freddy', 'malaver', 'Coca cola, Brawnie', '1, 1', '3250.00, 4030.00', '0', 'envio de comida solicitada por la app', 14000, 21280.00, 'Efectivo'),
(57, 26, '2024-12-04', 'Freddy', 'malaver', 'Brawnie, Coca cola, gomas trululu', '1, 1, 1', '4030.00, 3250.00, 4420.00', '0', '', 0, 11700.00, 'Nequi'),
(58, 26, '2024-12-05', 'Freddy', 'malaver', '', '', '', '0', 'envio de trago', 20000, 20000.00, 'Efectivo'),
(59, 26, '2024-12-05', 'Freddy', 'malaver', 'Brawnie', '1', '4030.00', '0', 'entrega de compras virtuales', NULL, 19030.00, 'Efectivo'),
(60, 26, '2024-12-05', 'Freddy', 'malaver', 'Brawnie, Coca cola', '1, 1', '4030.00, 3250.00', '0', 'entrega de compras virtuales', NULL, 22280.00, 'Nequi'),
(61, 26, '2024-12-06', 'Freddy', 'malaver', 'Brawnie, Coca cola, cafe aguila roja', '1, 1, 1', '4030.00, 3250.00, 4394.00', '0', 'entrega de compras virtuales', NULL, 26674.00, 'Nequi'),
(62, 26, '2024-12-06', 'Freddy', 'malaver', 'Coca cola', '1', '3250.00', '0', 'entrega de compras virtuales', NULL, 18250.00, '0'),
(63, 26, '2024-12-07', 'Freddy', 'malaver', 'Brawnie, Coca cola, gomas trululu', '1, 1, 1', '4030.00, 3250.00, 4420.00', '0', 'entrega de compras virtuales', 15000, 26700.00, '0'),
(64, 26, '2024-12-07', 'Freddy', 'malaver', 'Brawnie, Coca cola, aguardiente amarillo', '1, 1, 1', '4030.00, 3250.00, 35000.00', 'domicilio', 'entrega de compras virtuales', 15000, 57280.00, 'Efectivo'),
(65, 26, '2024-12-08', 'Freddy', 'malaver', 'whiskey jhonny walker azul, aguardiente amarillo', '1, 1', '230000.00, 35000.00', 'a beber en casa', 'envio de trago', 20000, 285000.00, 'Nequi');

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
(1, 35, 3100, 24, 11, 0, 5, 39, 4030.00, 930),
(2, 75, 2500, 73, 2, 0, 9, 83, 3250.00, 750),
(3, 11, 3380, -3, 14, 11, 2, 13, 4394.00, 1014),
(6, 35, 3380, 33, 2, 35, 5, 39, 4160.00, 780),
(8, 25, 2600, 23, 2, 25, 3, 28, 3380.00, 780),
(9, 40, 3400, 35, 5, 40, 8, 12, 4420.00, 1020),
(10, 100, 230000, -10, 10, 100, 0, 0, 230000.00, 0),
(11, 100, 35000, 90, 10, 70, 4, 3, 35000.00, 10500),
(12, 610, 3100, 609, 1, 200, 2, 1, 4030.00, 930),
(13, 120, 9600, 119, 1, 120, 18, 12, 9600.00, 2880),
(18, 100, 40000, 100, 0, 100, 15, 10, 40000.00, 12000),
(19, 270, 19000, 270, 0, 120, 8, 5, 19000.00, 5700),
(20, 200, 120000, 200, 0, 200, 30, 20, 120000.00, 36000),
(21, 100, 90000, 100, 0, 100, 15, 10, 117000.00, 27000);

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
(1, 'Cerveza poker.', 'Bavaria.', 'Bebida alcoholica.', 'Bebida alcoholica en botella 350 ml.'),
(2, 'Coca cola.', 'Coca cola.', 'Bebida gacificada.', 'Bebida gacificada negra, 350 ml presentacion personal.'),
(3, 'Cerveza aguila.', 'Bavaria.', 'Bebida alcoholica.', 'Bebida alcoholica en botella 350 ml.'),
(6, 'Cerveza aguila cero.', 'Bavaria.', 'Bebida alcoholica.', 'Bebida alcoholica en botella 350 ml.'),
(8, 'Budweiser', 'Anheuser-Busch inBev.', 'Bebida alcoholica.', 'Bebida alcoholica en botella 350 ml.'),
(9, 'Gomas trululu.', 'Italo', 'Gomas de dulce.', 'Gomas de dulces de sabores variados.'),
(10, 'Whiskey jhonny walker azul.', 'Diageo.', 'Botella de whiskey 12 años azul  ', 'Botella whiskey 12 años jhonny whalker azul 750ml.'),
(11, 'Aguardiente amarillo de caldas.', 'Licorera de caldas.', 'Bebida alcoholica', 'Bebida alcoholica en botella 750ml.'),
(12, 'Cerveza costeñita.', 'Bavaria.', 'Bebida alcoholica en botella.', 'Bebida alcoholica, cerveza en presentacion de 350ml botella vidrio.'),
(13, 'Smirnoff manzana.', 'Diageo.', 'Bebida alcoholica', 'Bebida alcoholica botella por 500 ml sabor whiskey y manzana.'),
(18, 'Ron viejo santafe.', 'Licorera de caldas.', 'Bebida alcoholica añejada. ', 'Botella por 1000ml de ron.'),
(19, 'Aguardiente nectar verde.', 'Nectar.', 'Bebida alcoholica.', 'Botella 450 ml de aguardiente verde.'),
(20, 'Brandy san juan.', 'Bodegas fundador.', 'Bebida alcoholica.', 'Botella 820 ml de brandy san juan.'),
(21, 'Brandy domecq.', 'Casa pedro domecq colombia.', 'Bebida alcoholica', 'Botella bebida brandy domecq por 900ml.');

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
(1, 1, 'Colombina', 89045681, 1, 'cll 26 # 11 32', 2147483647, 'colmsas@producto.com', 'colmsas@producto.com'),
(5, 2, 'coca cola', 2147483647, 1, '868968sad78d7sa', 4652815, 'proveedor@femsa.com', 'proveedor@femsa.com'),
(11, 3, 'Postobon', 13489034, 1, '0', 2147483647, 'Proveedores-postobon@postobon.com', 'Proveedores-postobon@postobon.com'),
(12, 6, 'nectar', 98709234, 1, '0', 65489754, 'nectar@proveedor.com', 'nectar@proveedor.com'),
(15, 9, 'Aguardiente del valle', 76423490, 1, '0', 7534812, 'Aguardiente.delvalle@contacto.com', 'Aguardiente.delvalle@contacto.com'),
(19, 8, 'bavaria', 34589712, 1, '0', 7864312, 'bavaria.proveedor@ventas.com', 'bavaria.proveedor@ventas.com');

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
(8, 'a beber en casa', 'envio de trago', 20000, 1, '0'),
(9, 'domicilio', 'entrega de compras virtuales', 15000, 1, 'Activo'),
(10, 'comidita a casa', 'envio de comida solicitada por la app', 14000, 1, 'activo'),
(11, 'compra boleteria', 'venta de boletas eventos especiales', 90000, 1, 'activo'),
(15, 'venta comida', 'venta de comida en restaurante', 30000, 1, 'activo');

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
  `Password` varchar(100) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Id_usuario`, `Usuario`, `Password`, `rol`, `estado`) VALUES
(1, 'Gisell', '$2y$10$cH6m.CVK4vNieFquyd18POOwhKe4fL/qxaY0kBgjlBD0GWPMxuvoO', 'Administrador', 'Habilitado'),
(3, 'ivonne', '$2y$10$AvP8HBsowR6LkrFc/DtU3ulcgn2M5yhgMB4oaidPan7', 'Usuario', 'Habilitado'),
(25, 'Nayibe', '$2y$10$wxkb8qMI37oxQWZdsx7Un.dOKTr9UQznx4CHGXZ4JzNBQhSjjsEGy', 'Usuario', 'Habilitado'),
(26, 'freddy', '$2y$10$pgqC2S3q3hAaNxht1An8i.sM4X/HY5D7pOKILlZoTVi/mX9Zi6st6', 'Usuario', '');

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
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`ID_Factura`),
  ADD KEY `Id_usuario` (`Id_usuario`);

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
  MODIFY `CodigoCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `ID_Factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `Codigoproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `Codigoproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`CodigoCliente`) REFERENCES `usuarios` (`Id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuarios` (`Id_usuario`) ON DELETE SET NULL,
  ADD CONSTRAINT `facturas_ibfk_2` FOREIGN KEY (`Id_usuario`) REFERENCES `usuarios` (`Id_usuario`);

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
