-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-06-2024 a las 08:39:19
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
  `Cedula` int(50) NOT NULL,
  `Direccion` varchar(150) NOT NULL,
  `Telefono` int(100) NOT NULL,
  `Correo_electronico` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `Codigofactura` int(11) NOT NULL,
  `CodigoCliente` int(11) NOT NULL,
  `Codigoproducto` int(11) NOT NULL,
  `Codigo` int(50) NOT NULL,
  `Total` int(150) NOT NULL,
  `Unidad_valor` int(150) NOT NULL,
  `Iva` int(150) NOT NULL,
  `Unidad` int(100) NOT NULL
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
  `Stock_minimo` int(50) NOT NULL,
  `Stock_maximo` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`Codigoproducto`, `Cantidad_producto`, `Precio`, `Entrada`, `Salida`, `Stock_minimo`, `Stock_maximo`) VALUES
(20, 10, 3200, 0, 0, 0, 0),
(21, 30, 2400, 0, 0, 0, 0),
(22, 20, 1200, 0, 0, 0, 0),
(23, 23, 3200, 0, 0, 0, 0),
(24, 10, 5400, 0, 0, 0, 0),
(25, 50, 3500, 0, 0, 0, 0),
(26, 20, 3500, 50, 7, 3, 22);

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
(20, 'brawnie', 'maria', 'torta negra', '0'),
(21, 'coca cola', 'coca cola', 'bebida', '0'),
(22, 'cafe aguila roja', 'aguila roja', 'cafe en bolso', '0'),
(23, 'brawnie', 'maria', 'torta negra', '0'),
(24, 'mustang azul', 'malboro', 'cigarrillo', '0'),
(25, 'cerveza', 'bavaria', 'alcoholica', '0'),
(26, 'cerveza', 'bavaria', 'alcoholica', 'hjvvbkjv');

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
(12, 21, 'alejandro z19', 2147483647, 34, '0', 300998745, 'leon@outlook.com', 'leon@outlook.com'),
(13, 22, 'Alejandro S.A.S', 1014305938, 8, '', 2147483647, 'sneider200973@hotmail.com', 'sneider200973@hotmail.com'),
(15, 24, 'Alejandro S.A.S', 1014305938, 8, '0', 2147483647, 'sneider200973@hotmail.com', 'sneider200973@hotmail.com'),
(19, 20, 'diana', 32384184, 212, '0', 2147483647, 'DIANA@GMAIL.COM', 'DIANA@GMAIL.COM'),
(20, 25, 'SIJAID', 12173713, 3131, '0', 2147483647, 'SSDNAKD@GMAIL', 'SNFAKJF.@GMAIL.COM');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `Codigo` int(11) NOT NULL,
  `Nombre_servicio` varchar(150) NOT NULL,
  `Tipo_servicio` varchar(150) NOT NULL,
  `Id_usuario` int(11) DEFAULT NULL,
  `Estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`Codigo`, `Nombre_servicio`, `Tipo_servicio`, `Id_usuario`, `Estado`) VALUES
(1, 'Trago hasta tu casa', 'Domicilios', 1, '0'),
(2, 'comidita a casa', 'Domicilios', 1, '0'),
(5, 'a casa seguro', 'transporte', 1, '0');

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
(2, 'freddy', '123456', 'Usuario');

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
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`Codigofactura`),
  ADD UNIQUE KEY `CodigoCliente` (`CodigoCliente`),
  ADD UNIQUE KEY `Codigoproducto` (`Codigoproducto`),
  ADD UNIQUE KEY `Codigo` (`Codigo`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD UNIQUE KEY `Codigoproducto` (`Codigoproducto`) USING BTREE;

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
  MODIFY `CodigoCliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `Codigofactura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `Codigoproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `Codigoproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`CodigoCliente`) REFERENCES `usuarios` (`Id_usuario`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`CodigoCliente`) REFERENCES `cliente` (`CodigoCliente`),
  ADD CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`Codigo`) REFERENCES `administrador` (`Codigo`),
  ADD CONSTRAINT `factura_ibfk_3` FOREIGN KEY (`Codigoproducto`) REFERENCES `producto` (`Codigoproducto`);

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `fk_inventario_producto` FOREIGN KEY (`Codigoproducto`) REFERENCES `producto` (`Codigoproducto`) ON DELETE CASCADE;

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
