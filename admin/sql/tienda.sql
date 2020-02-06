-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-02-2020 a las 23:19:33
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda`
--
CREATE DATABASE IF NOT EXISTS `tienda` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `tienda`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `precio` text COLLATE utf8_spanish_ci NOT NULL,
  `unidades` text COLLATE utf8_spanish_ci NOT NULL,
  `imagen` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `tipo`, `marca`, `precio`, `unidades`, `imagen`) VALUES
(9, 'VehÃ­culo radiocontrol', '3-6', 'Jueguettos', '2', '50', 'e8d871146220be52419f14742a093cd4.jpeg'),
(10, 'Lamborghini radiocontrol', '6-10', 'Lamborghini', '19.99', '9', '6343e55134c91818b39a6228cc69b290.jpeg'),
(11, 'Cubo de RubikÂ´s 3x3 Pro', '6-10', 'Speed Cube', '18.99', '21', 'f03663499efedd1f1448119c6a726171.jpeg'),
(12, 'Scientific Tools', '6-10', 'Juguettos', '29.99', '38', 'b0b66ca3f431e328ff4ab7f8a1bb9a1b.jpeg'),
(13, 'Arrullo, Patucos y Peluche Azul', '0-3', 'FLYBYFLY', '25.95', '78', '12314b4344207423d584d942f45db085.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` text COLLATE utf8_spanish_ci NOT NULL,
  `correo` text COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `tipo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` int(9) NOT NULL,
  `imagen` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `correo`, `password`, `tipo`, `telefono`, `imagen`, `fecha`) VALUES
(52, 'Administrador', 'ROOT', 'admin@admin.com', 'admin', 'ADMIN', 426759813, 'aa7d8a8ec4a1b914d1b9897dd1af1c1b.png', '05/02/2020'),
(55, 'RaÃºl', 'MuÃ±oz', 'raulbohonal06@gmail.com', 'raul', 'ADMIN', 321654987, 'e3720785355b16f6ee973091d48169ef.png', '20/07/1998'),
(56, 'Prueba', 'EsNormal', 'prueba@prueba.com', 'prueba', 'USER', 987159654, '52250a37057294d4c339bfe5e497ab22.png', '05/02/2020');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
