-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-10-2024 a las 14:20:27
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
-- Base de datos: `app_inventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

CREATE TABLE `prestamo` (
  `ID_PRESTAMO` int(11) NOT NULL,
  `EMAIL_PRESTAMISTA` varchar(200) DEFAULT NULL,
  `COD_PRODUCTO` int(11) DEFAULT NULL,
  `CANTIDAD` int(11) DEFAULT NULL,
  `FECHA_PRESTAMO` date DEFAULT NULL,
  `FECHA_DEVOLUCION` date DEFAULT NULL,
  `USUARIO_PRESTAMO` varchar(200) DEFAULT NULL,
  `ESTADO` enum('EN ESPERA','ACEPTADO','DENEGADO','ESPERA DEVOLUCION','DEVUELTO') DEFAULT NULL,
  `EVENTO` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`ID_PRESTAMO`, `EMAIL_PRESTAMISTA`, `COD_PRODUCTO`, `CANTIDAD`, `FECHA_PRESTAMO`, `FECHA_DEVOLUCION`, `USUARIO_PRESTAMO`, `ESTADO`, `EVENTO`) VALUES
(26, 'shade@gmail.com', 1, 4, '2024-10-16', '2024-10-23', 'shade@gmail.com', 'ACEPTADO', NULL),
(29, 'shade@gmail.com', 3, 3, '2024-10-16', '2024-11-16', 'usuario@gmail.com', 'EN ESPERA', NULL),
(30, 'shade@gmail.com', 5, 1, '2024-10-16', '2024-11-16', 'usuario@gmail.com', 'EN ESPERA', NULL),
(31, 'shade@gmail.com', 1, 1, '2024-10-16', '2024-11-16', 'usuario@gmail.com', 'ACEPTADO', NULL),
(32, 'shade@gmail.com', 3, 4, '2024-10-16', '2024-11-16', 'usuario@gmail.com', 'ACEPTADO', NULL),
(33, 'shade@gmail.com', 2, 3, '2024-10-16', '2024-11-16', 'shade@gmail.com', 'ACEPTADO', NULL),
(34, 'shade@gmail.com', 5, 2, '2024-10-17', '2024-11-17', 'shade@gmail.com', 'ACEPTADO', NULL),
(35, 'shade@gmail.com', 7, 3, '2024-10-17', '2024-11-17', 'shade@gmail.com', 'ACEPTADO', NULL),
(36, 'shade@gmail.com', 1, 2, '2024-10-17', '2024-11-17', 'shade@gmail.com', 'ACEPTADO', 'Furbo'),
(37, NULL, 2, 3, '2024-10-17', '2024-11-17', 'shade@gmail.com', 'EN ESPERA', NULL),
(38, NULL, 10, 2, '2024-10-17', '2024-11-17', 'shade@gmail.com', 'EN ESPERA', 'Montaje');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `SN` int(11) NOT NULL,
  `NOMBRE` varchar(200) DEFAULT NULL,
  `FECHA_COMPRA` date DEFAULT NULL,
  `PRECIO` float DEFAULT NULL,
  `CATEGORIA` varchar(200) DEFAULT NULL,
  `CANTIDAD` int(11) DEFAULT NULL,
  `STOCK` int(11) DEFAULT NULL,
  `PRESTADO` int(11) DEFAULT NULL,
  `MALGASTADO` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`SN`, `NOMBRE`, `FECHA_COMPRA`, `PRECIO`, `CATEGORIA`, `CANTIDAD`, `STOCK`, `PRESTADO`, `MALGASTADO`) VALUES
(1, 'MacBook Pro 20', '2024-10-17', 2500, 'Desarrollo Web +', 30, 20, 10, 2),
(2, 'Monitor 4K LG 27\"', '2022-09-18', 500, 'Multimedia', 30, 20, 8, 2),
(3, 'Teclado Mecánico Logitech G Pro', '2023-03-12', 150, 'Desarrollo Web', 30, 18, 10, 2),
(4, 'Mouse Inalámbrico Logitech MX Master 3', '2023-02-05', 100, 'Desarrollo Web', 30, 25, 3, 2),
(5, 'Servidor DELL PowerEdge R540', '2022-11-01', 4000, 'Mantenimiento de Servidores', 30, 28, 2, 0),
(6, 'Cámara Sony Alpha A6400', '2023-01-10', 1200, 'Multimedia', 30, 25, 3, 2),
(7, 'Tablet iPad Pro 12.9\"', '2023-06-20', 1400, 'Desarrollo Web', 30, 20, 8, 2),
(8, 'Micrófono Blue Yeti USB', '2022-12-15', 130, 'Multimedia', 30, 25, 3, 2),
(9, 'Servidor NAS Synology DS920+', '2023-07-05', 800, 'Mantenimiento de Servidores', 30, 25, 3, 2),
(10, 'Tarjeta Gráfica NVIDIA RTX 3080', '2023-08-30', 1200, 'Multimedia', 30, 26, 4, 2),
(93, 'Micrófono Pro X Max', '2024-10-17', 200, 'Audio', 30, 25, 3, 2),
(104, 'Ratón Logitech MX Master 3', '2023-03-05', 85, 'Informática', 80, 70, 7, 3),
(105, 'Disco Duro Externo WD 1TB', '2023-04-10', 60, 'Informática', 120, 110, 5, 5),
(106, 'Memoria RAM Corsair 16GB DDR4', '2023-05-15', 150, 'Informática', 40, 35, 3, 2),
(107, 'Placa Base MSI B550', '2023-06-01', 200, 'Informática', 25, 20, 3, 2),
(108, 'Procesador Intel i7-11700K', '2023-06-10', 320, 'Informática', 30, 28, 1, 1),
(109, 'Tarjeta Gráfica NVIDIA RTX 3060', '2023-07-20', 450, 'Informática', 15, 10, 4, 1),
(110, 'Fuente de Alimentación Corsair 650W', '2023-08-05', 80, 'Informática', 50, 45, 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `EMAIL` varchar(200) NOT NULL,
  `CONTRASENA` varchar(500) DEFAULT NULL,
  `ROL` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`EMAIL`, `CONTRASENA`, `ROL`) VALUES
('daniel@gmail.com', '$2y$13$./Fs.O9mieuKt67bq.puduNypbOdy1j0IbAuoF3P65rWQD75Z7uSy', 1),
('shade@gmail.com', '$2y$13$0ZhO6PCo.VRDWCNMciR01OzuznEt9yRGn5TsykbFAD6lKodeNpsoK', 1),
('usuario@gmail.com', '$2y$13$JpS2DmzhHx6cqLndp9NvROKF8A1VXE7GUiH29yMeiTHRDDHJZnwmi', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`ID_PRESTAMO`),
  ADD KEY `FK_EMAIL_PRESTAMISTA` (`EMAIL_PRESTAMISTA`),
  ADD KEY `FK_COD_PRODUCTO` (`COD_PRODUCTO`),
  ADD KEY `FK_USUARIO_PRESTAMO` (`USUARIO_PRESTAMO`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`SN`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`EMAIL`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `ID_PRESTAMO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `FK_COD_PRODUCTO` FOREIGN KEY (`COD_PRODUCTO`) REFERENCES `producto` (`SN`),
  ADD CONSTRAINT `FK_EMAIL_PRESTAMISTA` FOREIGN KEY (`EMAIL_PRESTAMISTA`) REFERENCES `usuario` (`EMAIL`),
  ADD CONSTRAINT `FK_USUARIO_PRESTAMO` FOREIGN KEY (`USUARIO_PRESTAMO`) REFERENCES `usuario` (`EMAIL`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
