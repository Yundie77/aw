-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-03-2025 a las 12:58:36
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
-- Base de datos: `awp2`
--

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Compra'),
(2, 'Comida'),
(3, 'Ocio'),
(4, 'Ropa'),
(5, 'Salud'),
(6, 'Transporte'),
(7, 'Otros'),
(8, 'Salario'),
(9, 'Deporte');

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id`, `usuario_id`, `tipo`, `categoria_id`, `monto`, `fecha`, `comentario`) VALUES
(13, 1, 'Ingreso', 2, 1.00, '2025-03-03', ''),
(14, 1, 'Gasto', 3, 65.00, '2025-03-03', 'Cine'),
(15, 1, 'Ingreso', 8, 600.00, '2025-03-01', ''),
(16, 1, 'Ingreso', 2, 22.00, '2025-03-05', 'dss'),
(18, 1, 'Ingreso', 3, 56.00, '2025-03-03', 'Cine'),
(19, 1, 'Gasto', 9, 32.00, '2025-03-03', ''),
(20, 1, 'Gasto', 4, 33.00, '2025-03-03', 'Compra'),
(21, 1, 'Gasto', 6, 33.00, '2025-03-05', 'metroo'),
(23, 1, 'Ingreso', 7, 50.00, '2025-03-07', 'Devolucion'),
(24, 1, 'Gasto', 2, 23.00, '2025-03-07', 'Lidl'),
(25, 1, 'Gasto', 4, 54.00, '2025-03-07', '');

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`) VALUES
(1, 'Ejemplo', 'ejemplo@correo.com', 'claveEncriptada', 'usuario');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
