-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-12-2020 a las 00:48:31
-- Versión del servidor: 10.4.13-MariaDB
-- Versión de PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lacomanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alimentos`
--

CREATE TABLE `alimentos` (
  `alimentoId` int(11) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `precio` float NOT NULL,
  `minutosDePreparacion` int(11) NOT NULL,
  `createDttm` varchar(30) NOT NULL,
  `updateDttm` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `alimentos`
--

INSERT INTO `alimentos` (`alimentoId`, `categoria`, `descripcion`, `precio`, `minutosDePreparacion`, `createDttm`, `updateDttm`) VALUES
(1, 'comida', 'Churrasco roast beaf', 150.2, 35, '2020-11-22 22:03:06', '2020-11-22 22:22:47'),
(2, 'vino', 'Stella Artois', 50.3, 5, '2020-11-22 22:04:42', '2020-11-22 22:22:24'),
(4, 'postre', 'Helado', 50.5, 10, '2020-11-22 22:25:12', '2020-11-22 22:25:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandas`
--

CREATE TABLE `comandas` (
  `comandaCode` varchar(5) NOT NULL,
  `legajo` int(11) NOT NULL,
  `mesaCode` varchar(5) NOT NULL,
  `horaInicio` varchar(30) NOT NULL,
  `horaFin` varchar(30) NOT NULL,
  `createDttm` varchar(30) NOT NULL,
  `updateDttm` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `comandas`
--

INSERT INTO `comandas` (`comandaCode`, `legajo`, `mesaCode`, `horaInicio`, `horaFin`, `createDttm`, `updateDttm`) VALUES
('7d9Pn', 1234, 'ABCD2', '2020-11-30 20:43:49', '2020-11-30 20:46:27', '2020-11-30 20:43:49', '2020-11-30 20:46:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `legajo` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `ocupacion` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `createDttm` varchar(30) NOT NULL,
  `updateDttm` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`legajo`, `email`, `nombre`, `apellido`, `ocupacion`, `estado`, `createDttm`, `updateDttm`) VALUES
(1233, 'luli@gmail.com', 'luciana', 'gomez', 'bartender', 'activo', '2020-11-21 16:38:00', '2020-11-22 19:23:56'),
(1234, 'fer@gmail.com', 'fernanda', 'lopez', 'mozo', 'activo', '2020-11-19 22:22:32', '2020-11-21 16:55:32'),
(1235, 'marina@gmail.com', 'marina', 'fernandez', 'cocinero', 'activo', '2020-11-25 22:23:48', '2020-11-25 22:23:48'),
(1236, 'gaby@gmail.com', 'gabriela', 'pereira', 'cervecero', 'activo', '2020-11-25 22:25:23', '2020-11-25 22:25:23'),
(1237, 'diana@gmail.com', 'diana', 'lopez', 'cocinero', 'borrado', '2020-11-29 15:34:35', '2020-11-29 15:37:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `encuestaId` int(11) NOT NULL,
  `puntajeMesa` int(11) NOT NULL,
  `puntajeRestaurante` int(11) NOT NULL,
  `puntajeMozo` int(11) NOT NULL,
  `puntajeCocinero` int(11) NOT NULL,
  `descripcion` varchar(66) NOT NULL,
  `createDttm` varchar(30) NOT NULL,
  `updateDttm` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`encuestaId`, `puntajeMesa`, `puntajeRestaurante`, `puntajeMozo`, `puntajeCocinero`, `descripcion`, `createDttm`, `updateDttm`) VALUES
(6, 5, 7, 3, 5, 'Volveria de nuevo', '2020-11-30 20:46:23', '2020-11-30 20:46:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `mesaCode` varchar(5) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `foto` varchar(150) NOT NULL,
  `createDttm` varchar(30) NOT NULL,
  `updateDttm` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`mesaCode`, `estado`, `foto`, `createDttm`, `updateDttm`) VALUES
('ABCD1', 'cerrada', '', '2020-11-22 18:04:53', '2020-11-22 18:04:53'),
('ABCD2', 'cerrada', '', '2020-11-22 18:04:55', '2020-11-30 20:46:27'),
('ABCD3', 'cerrada', '', '2020-11-22 18:04:58', '2020-11-29 16:04:25'),
('ABCD4', 'cerrada', '', '2020-11-22 18:05:01', '2020-11-22 18:05:01'),
('ABCD5', 'cerrada', '', '2020-11-22 18:05:03', '2020-11-22 18:05:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `pedidoId` int(11) NOT NULL,
  `legajo` int(11) NOT NULL,
  `comandaCode` varchar(5) NOT NULL,
  `alimentoId` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `horaInicio` varchar(30) NOT NULL,
  `horaFin` varchar(30) NOT NULL,
  `createDttm` varchar(30) NOT NULL,
  `updateDttm` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`pedidoId`, `legajo`, `comandaCode`, `alimentoId`, `cantidad`, `estado`, `horaInicio`, `horaFin`, `createDttm`, `updateDttm`) VALUES
(17, 1235, '7d9Pn', 1, 1, 'entregado', '2020-11-30 20:45:32', '2020-11-30 20:45:46', '2020-11-30 20:44:52', '2020-11-30 20:46:01'),
(18, 1233, '7d9Pn', 2, 1, 'entregado', '2020-11-30 20:45:15', '2020-11-30 20:45:49', '2020-11-30 20:44:56', '2020-11-30 20:46:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `email` varchar(100) NOT NULL,
  `clave` varchar(150) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `loginDttm` varchar(30) DEFAULT NULL,
  `createDttm` varchar(30) NOT NULL,
  `updateDttm` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`email`, `clave`, `tipo`, `estado`, `loginDttm`, `createDttm`, `updateDttm`) VALUES
('belu@gmail.com', '2221cb2eaea331f8a5249a772818de5ead80f087', 'socio', 'activo', '2020-11-24 20:56:39', '2020-11-15 16:20:03', '2020-11-24 20:56:39'),
('diana@gmail.com', 'c5f6d425bc34235f7799ba996b779954f809fcab', 'empleado', 'activo', NULL, '2020-11-29 15:34:02', '2020-11-29 15:34:02'),
('fer@gmail.com', 'c5f6d425bc34235f7799ba996b779954f809fcab', 'empleado', 'activo', '2020-11-25 21:56:32', '2020-11-15 20:32:00', '2020-11-25 21:56:32'),
('gaby@gmail.com', 'c5f6d425bc34235f7799ba996b779954f809fcab', 'empleado', 'activo', '2020-11-25 22:25:35', '2020-11-25 22:24:52', '2020-11-25 22:25:35'),
('juampi@gmail.com', '2221cb2eaea331f8a5249a772818de5ead80f087', 'socio', 'borrado', '2020-11-15 16:32:01', '2020-11-15 16:20:03', '2020-11-19 19:28:56'),
('luli@gmail.com', 'c5f6d425bc34235f7799ba996b779954f809fcab', 'empleado', 'activo', '2020-11-25 21:55:52', '2020-11-21 16:37:55', '2020-11-25 21:55:52'),
('marina@gmail.com', 'c5f6d425bc34235f7799ba996b779954f809fcab', 'empleado', 'activo', '2020-11-25 22:24:16', '2020-11-25 22:23:03', '2020-11-25 22:24:16'),
('maru@gmail.com', '2221cb2eaea331f8a5249a772818de5ead80f087', 'socio', 'activo', '2020-11-15 16:31:45', '2020-11-15 16:20:03', '2020-11-15 16:20:03'),
('pedro@gmail.com', '18b5e3e49337b9ae324daf50b80a2de6b7958803', 'cliente', 'activo', '2020-11-25 22:26:31', '2020-11-15 20:32:35', '2020-11-25 22:26:31');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alimentos`
--
ALTER TABLE `alimentos`
  ADD PRIMARY KEY (`alimentoId`);

--
-- Indices de la tabla `comandas`
--
ALTER TABLE `comandas`
  ADD PRIMARY KEY (`comandaCode`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`legajo`);

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`encuestaId`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`mesaCode`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`pedidoId`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alimentos`
--
ALTER TABLE `alimentos`
  MODIFY `alimentoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `encuestaId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `pedidoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
