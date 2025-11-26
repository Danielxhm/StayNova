-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 26-11-2025 a las 02:06:47
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.1.2-1ubuntu2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `staynova`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alojamientos`
--

CREATE TABLE `alojamientos` (
  `Id` int NOT NULL,
  `Nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Descripcion` text COLLATE utf8mb4_general_ci,
  `Precio` decimal(10,2) NOT NULL,
  `Ubicacion` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ImagenUrl` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Amenidades` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `Activo` tinyint(1) DEFAULT '1',
  `FechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UsuarioCreador` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alojamientos`
--

INSERT INTO `alojamientos` (`Id`, `Nombre`, `Descripcion`, `Precio`, `Ubicacion`, `ImagenUrl`, `Amenidades`, `Activo`, `FechaCreacion`, `UsuarioCreador`) VALUES
(1, 'Cabaña en el Bosque', 'Una hermosa cabaña para escapar de la ciudad.', 120.00, 'Montaña El Pital', 'https://elsalvador.travel/system/wp-content/uploads/2022/12/DestinationPital.jpg', NULL, 1, '2025-11-05 23:57:55', 1),
(2, 'Apartamento de Playa', 'Vistas increíbles al océano.', 250.50, 'Playa El Tunco', 'https://elsalvador.travel/system/wp-content/uploads/2020/01/EL-TUNCO.jpg', NULL, 1, '2025-11-05 23:57:55', 1),
(3, 'Casa Colonial', 'Encanto histórico en el centro de la ciudad.', 150.00, 'Suchitoto', 'https://upload.wikimedia.org/wikipedia/commons/2/21/Casas_de_Suchitoto.jpg', 'Generales', 1, '2025-11-05 23:57:55', 1),
(4, 'Alojamiento entero: loft en El Sunzal, El Salvador', 'Imagina despertar con toda una experiencia costera ante ti, el contraste perfecto entre el cielo, la montaña y el mar.\r\nDisfruta de una estancia relajante en nuestro acogedor loft. \r\nEste espacio moderno y cómodo está diseñado para ofrecerte una experiencia placentera a minutos de la playa.\r\nEl loft cuenta con cocina equipada y balcón con bonitas vistas.\r\nCerca de los mejores restaurantes locales, centros comerciales, y a 4 min de Surf City. Es el lugar perfecto para unas vacaciones relajantes!', 290.00, 'Sunzal', 'https://a0.muscache.com/im/pictures/hosting/Hosting-1245794946723521270/original/e5466b0c-a08a-4c2f-a64d-c902a02f9254.jpeg?im_w=1200', 'Wifi', 1, '2025-11-24 23:56:57', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `Id` int NOT NULL,
  `UsuarioId` int NOT NULL,
  `AlojamientoId` int NOT NULL,
  `FechaCreacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `favoritos`
--

INSERT INTO `favoritos` (`Id`, `UsuarioId`, `AlojamientoId`, `FechaCreacion`) VALUES
(11, 1, 3, '2025-11-24 22:51:09'),
(12, 1, 1, '2025-11-24 22:51:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarioalojamientos`
--

CREATE TABLE `usuarioalojamientos` (
  `Id` int NOT NULL,
  `UsuarioId` int NOT NULL,
  `AlojamientoId` int NOT NULL,
  `FechaSeleccion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarioalojamientos`
--

INSERT INTO `usuarioalojamientos` (`Id`, `UsuarioId`, `AlojamientoId`, `FechaSeleccion`) VALUES
(1, 2, 1, '2025-11-05 23:57:55'),
(2, 3, 3, '2025-11-07 00:39:21'),
(3, 3, 1, '2025-11-07 00:39:37'),
(4, 3, 2, '2025-11-07 00:39:40'),
(8, 5, 2, '2025-11-25 22:33:34'),
(10, 5, 4, '2025-11-25 23:17:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `Id` int NOT NULL,
  `UserName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `PasswordHash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Rol` enum('Usuario','Administrador') COLLATE utf8mb4_general_ci DEFAULT 'Usuario',
  `FechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Activo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`Id`, `UserName`, `Email`, `PasswordHash`, `Rol`, `FechaCreacion`, `Activo`) VALUES
(1, 'admin', 'admin@mail.com', '$2y$10$GA3fazwAiu7NQ23AyhuC.ORZJM4bfw/gWkvx/llZdWsu4OLUH4RXq', 'Administrador', '2025-11-05 23:57:55', 1),
(2, 'usuario_demo', 'user@mail.com', '$2y$10$A5g1H.gTwc4w5gwg2xJ.X.3NvBfBAlmGzcsdPYiJzHRf6fARsOMle', 'Usuario', '2025-11-05 23:57:55', 1),
(3, 'aad', 'ada@gmail.com', '$2y$10$ZuUbJNlWQH1J93aPdYZXYeBz01VUE4F.lXAjAwoye5eu0ITaD1clW', 'Usuario', '2025-11-06 02:08:37', 1),
(5, 'daniel', 'da@gmail.com', '$2y$10$fd8AHMAqBa3I.8ZMY8YjZ.qKtlTcOEDqnzkjHwJtsUzFqh7Wqh7uq', 'Usuario', '2025-11-25 19:25:09', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alojamientos`
--
ALTER TABLE `alojamientos`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UsuarioCreador` (`UsuarioCreador`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `UniqueFavorito` (`UsuarioId`,`AlojamientoId`),
  ADD KEY `AlojamientoId` (`AlojamientoId`);

--
-- Indices de la tabla `usuarioalojamientos`
--
ALTER TABLE `usuarioalojamientos`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `UniqueUsuarioAlojamiento` (`UsuarioId`,`AlojamientoId`),
  ADD KEY `AlojamientoId` (`AlojamientoId`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alojamientos`
--
ALTER TABLE `alojamientos`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `usuarioalojamientos`
--
ALTER TABLE `usuarioalojamientos`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alojamientos`
--
ALTER TABLE `alojamientos`
  ADD CONSTRAINT `alojamientos_ibfk_1` FOREIGN KEY (`UsuarioCreador`) REFERENCES `Usuarios` (`Id`);

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`UsuarioId`) REFERENCES `Usuarios` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`AlojamientoId`) REFERENCES `alojamientos` (`Id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarioalojamientos`
--
ALTER TABLE `usuarioalojamientos`
  ADD CONSTRAINT `usuarioalojamientos_ibfk_1` FOREIGN KEY (`UsuarioId`) REFERENCES `Usuarios` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarioalojamientos_ibfk_2` FOREIGN KEY (`AlojamientoId`) REFERENCES `alojamientos` (`Id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
