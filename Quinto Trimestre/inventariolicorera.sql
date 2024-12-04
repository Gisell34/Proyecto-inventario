-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-12-2024 a las 01:48:58
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

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`Codigo`, `Nombres`, `Cargo`, `Correo_electronico`) VALUES
(1, 'Gisell Navarrete', 'Administrador', 'gngisell.26@gmail.com');

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
(2, 'Freddy Andres', 'Mejia malaver', 1024789364, 'cra 3 # 12 -24', 'Bogota', 2147483647, 'Fmalaver@gmail.com');

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
(1, 2, '2024-12-03', 'Freddy Andres', 'Mejia malaver', 'Cerveza aguila cero., Cerveza poker., Coca cola., Aguardiente amarillo de caldas.', '1, 1, 1, 1', '4160.00, 6890.00, 3250.00, 35000.00', 'a beber en casa', 'envio de trago', 20000, 69300.00, 'Efectivo'),
(2, 2, '2024-12-03', 'Freddy Andres', 'Mejia malaver', 'Cerveza poker., Whiskey jhonny walker azul.', '25, 1', '6890.00, 230000.00', '', '', 0, 402250.00, 'Nequi');

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
(1, 55, 5300, 44, 11, 0, 2, 1, 6890.00, 1590),
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
(21, 100, 90000, 100, 0, 100, 15, 10, 117000.00, 27000),
(22, 100, 95000, 100, 0, 100, 15, 10, 123500.00, 28500),
(23, 80, 16000, 80, 0, 80, 12, 8, 20800.00, 4800);

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
(21, 'Brandy domecq.', 'Casa pedro domecq colombia.', 'Bebida alcoholica', 'Botella bebida brandy domecq por 900ml.'),
(22, 'Crema de whiskey balleys', 'balleys', 'Crema de whiskey', 'Botella por 800 ml de crema de whiskey marca balleys'),
(23, 'Smirnoff ice.', 'smirnoff.', 'bebida alcoholica de vokca', 'bebida alcoholica de vokca en presentacion botella 500 ml ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `Codigoproveedor` int(11) NOT NULL,
  `Codigoproducto` int(11) NOT NULL,
  `Nombre_proveedor` varchar(150) NOT NULL,
  `Nit` int(50) NOT NULL,
  `Direccion` text NOT NULL,
  `Telefono` int(100) NOT NULL,
  `Correo_electronico` varchar(100) NOT NULL,
  `Correo_factura` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`Codigoproveedor`, `Codigoproducto`, `Nombre_proveedor`, `Nit`, `Direccion`, `Telefono`, `Correo_electronico`, `Correo_factura`) VALUES
(1, 1, 'Bavaria', 12089076, 'diag 80 # 56 -09', 4568723, 'Proveedores-bavaria@bavaria.com', 'facturacion-bavaria@bavaria.com'),
(2, 2, 'Anheuser Busch inBev', 9812054, 'cra 27 # 18 -32', 2147483647, 'Entregas@Anheuser-BuschinBev.com', 'Entregas@Anheuser-BuschinBev.com'),
(3, 3, 'Coca cola', 140654008, 'av cll 45 # 11 -89', 65489754, 'Cocacola@proveedor.es', 'Cocacola@proveedor.es'),
(4, 6, 'Italo', 87345011, 'cra 3 # 12 -24', 6352420, 'entregasitalo@proveedor.com', 'entregasitalo@proveedor.com'),
(5, 8, 'Diageo', 123760990, 'diag 80 # 56 -09', 3457890, 'diageo-proveedor@proveedor.com', 'diageo-proveedor@proveedor.com'),
(6, 9, 'Licorera de caldas', 100900306, 'autop 45 # 120 86', 6540971, 'Contacto@licoresdecaldas.com', 'Contacto@licoresdecaldas.com');

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
(1, 'a beber en casa', 'envio de trago', 20000, 1, 'Activo'),
(2, 'domicilio', 'entrega de compras virtuales', 15000, 1, 'Activo'),
(3, 'comidita a casa', 'envio de comida solicitada por la app', 14000, 1, 'activo'),
(4, 'compra boleteria', 'venta de boletas eventos especiales', 90000, 1, 'activo'),
(5, 'venta comida', 'venta de comida en restaurante', 30000, 1, 'activo');

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
(2, 'Freddy', '$2y$10$kAf6dodxzIW0H/C5oAmxPeM7wxpF7SmYfGkZX0kXlMZavzA6RzKCS', 'Usuario', '');

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
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `CodigoCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `ID_Factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `Codigoproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `Codigoproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
