-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 16-09-2020 a las 16:37:17
-- Versión del servidor: 10.5.5-MariaDB-1:10.5.5+maria~focal
-- Versión de PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `database`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coches`
--

CREATE TABLE `coches` (
  `marca_modelo` text NOT NULL,
  `matricula` VARCHAR(7) NOT NULL,
  `color` text NOT NULL,
  `kilometros` int NOT NULL,
  `CV` int NOT NULL,
  `año` int NOT NULL

  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `usuarios` (
  `nombre_apellidos` TEXT NOT NULL,
  `dni` VARCHAR(9) NOT NULL,
  `telefono` VARCHAR(9) NOT NULL,
  `fecha_nacimiento` DATE NOT NULL,
  `email` VARCHAR(55) NOT NULL,
  `username` VARCHAR(25)  NOT NULL,
  `password`  VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `coches` (`matricula`, `marca_modelo`, `color`, `kilometros`,`CV`,`año`) VALUES
('1234BBC', 'kia miraz 5.7L V8', 'azul', 123454333,140,'2013'),
('3563DBF', 'seat grandes 1.2 TSI', 'negro', 199933,130,'2001');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `coches`
--
ALTER TABLE `coches` ADD PRIMARY KEY (`matricula`);

ALTER TABLE `usuarios` ADD PRIMARY KEY (`dni`), ADD UNIQUE KEY (`username`);
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
