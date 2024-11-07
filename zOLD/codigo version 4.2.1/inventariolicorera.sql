-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-09-2024 a las 00:50:05
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
(7, 'lucila', 'romero', 45678102, 'cra 3 # 12 -24', 'bogota', 6890544, 'ghtirusfv@htor.com'),
(8, 'sebastian', 'rojas', 67543290, 'diag 11 # 24 67', 'bogota', 9087080, 'huytr@hhhit.com'),
(9, 'luis fernando', 'romero', 19875241, 'diag 80 # 56 -09', 'bogota', 2147483647, 'huytr@hhhit.com'),
(15, 'samanta', 'gomez', 63427, 'diag 59 # 11 - 32', 'medellin', 6540987, 'marthahsfst@gdtara.com'),
(16, 'gabriel', 'navarrete', 2147483647, 'diag 80 # 56 -09', 'medellin', 7310936, 'hsgdts@mhgsu.com'),
(18, 'rubi', 'preciado', 2147483647, 'cra 69 # 56 - 20', 'cauca', 65489754, 'gdfsysh@hjgays.com'),
(20, 'cristian', 'patino', 36452947, 'cra 3 # 12 -24', 'bogota', 7653487, 'jsgsfstsgs@jjhsh.com'),
(21, 'diana', 'pineda', 11178654, 'diag 80 # 56 -09', 'bogota', 8875433, 'jsgsfstsgs@jjhsh.com');

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
(18, NULL, '0000-00-00', '', 1, 14000, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_compras`
--

CREATE TABLE `detalles_compras` (
  `id_detalle` int(11) NOT NULL,
  `id_compra` int(11) DEFAULT NULL,
  `codigoproducto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `tipo_producto` enum('producto','servicio') NOT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_compras`
--

INSERT INTO `detalles_compras` (`id_detalle`, `id_compra`, `codigoproducto`, `cantidad`, `tipo_producto`, `precio`) VALUES
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
(19, 18, 6, 1, 'producto', 0.00);

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
  `Fecha` date NOT NULL,
  `Nombre_cliente` varchar(255) NOT NULL,
  `Apellido_cliente` varchar(255) NOT NULL,
  `Productos` text NOT NULL,
  `Cantidad` int(50) NOT NULL,
  `Precio` int(50) NOT NULL,
  `Total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`ID_Factura`, `Fecha`, `Nombre_cliente`, `Apellido_cliente`, `Productos`, `Cantidad`, `Precio`, `Total`) VALUES
(1, '2024-08-29', 'freddy andres', 'mejia malaver', 'brawnie, cocacola', 0, 0, 34840.00),
(2, '2024-08-29', 'freddy andres', 'mejia malaver', 'brawnie, cocacola', 0, 0, 34840.00);

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
(7, 'lucila', '$2y$10$eC0oHYpVgfSfHq6lkakhY.DlKrHyonTlFJQFuhBjdzE', 'Usuario'),
(8, 'sebas', '$2y$10$nhje5GF07z7Ty6C1RqtRBOmL6pkjEJ6nZ9R5FBjAIrl', 'Usuario'),
(9, 'luis', '$2y$10$xtJx5WOoxPVXeanlp1tMAebx2FErrIhe4ePXhUTIwOh', 'Usuario'),
(15, 'samanta', '09876', 'Usuario'),
(16, 'gabriel', '', 'Usuario'),
(18, 'rubi', '$2y$10$F5CoDq7i3A5xpICtP3tKCu/.9DXDXf3d9xJTmZX8FZU', 'Usuario'),
(19, 'cristian', '$2y$10$wVL6mdbbBh2kvbhLtd1ioe9YowcwLf3oGYCvuLagXW9', 'Usuario'),
(20, 'cristian', '$2y$10$Nft5mX.t97lNrz.iJYNKWuYrByCkKrXYtU28XCark9K', 'Usuario'),
(21, 'diana', '$2y$10$sqquhYZpKeo3CAreWiT5AuBPWuNbIWdFymlJbwAFmUR', 'Usuario');

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
  ADD KEY `codigoproducto` (`codigoproducto`);

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
  ADD PRIMARY KEY (`ID_Factura`);

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
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `detalles_compras`
--
ALTER TABLE `detalles_compras`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id_detalle_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `ID_Factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`CodigoCliente`) REFERENCES `usuarios` (`Id_usuario`);

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`Id_usuario`),
  ADD CONSTRAINT `fk_cliente` FOREIGN KEY (`id_usuario`) REFERENCES `cliente` (`CodigoCliente`),
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `cliente` (`CodigoCliente`);

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

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`CodigoCliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
