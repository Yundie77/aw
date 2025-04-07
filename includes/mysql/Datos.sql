-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-04-07 19:44:47
-- 服务器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `awp2`
--

--
-- 转存表中的数据 `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `usuario_id`) VALUES
(1, 'Compra', 1),
(2, 'Comida', 1),
(3, 'Ocio', 1),
(4, 'Ropa', 1),
(5, 'Salud', 1),
(6, 'Transporte', 1),
(7, 'Otros', 1),
(8, 'Salario', 1),
(9, 'Deporte', 1),
(10, 'HES', 1),
(11, 'GFGF', 1),
(12, 'TE', 1),
(13, 'wert', 1),
(14, 'Compra', 2),
(15, 'Compra', 4),
(16, 'Compra', 5),
(17, 'Compra', 1),
(18, 'Compra', 3),
(19, 'Comida', 2),
(20, 'Comida', 4),
(21, 'Comida', 5),
(22, 'Comida', 1),
(23, 'Comida', 3),
(24, 'Ocio', 2),
(25, 'Ocio', 4),
(26, 'Ocio', 5),
(27, 'Ocio', 1),
(28, 'Ocio', 3),
(29, 'Ropa', 2),
(30, 'Ropa', 4),
(31, 'Ropa', 5),
(32, 'Ropa', 1),
(33, 'Ropa', 3),
(34, 'Salud', 2),
(35, 'Salud', 4),
(36, 'Salud', 5),
(37, 'Salud', 1),
(38, 'Salud', 3),
(39, 'Transporte', 2),
(40, 'Transporte', 4),
(41, 'Transporte', 5),
(42, 'Transporte', 1),
(43, 'Transporte', 3),
(44, 'Otros', 2),
(45, 'Otros', 4),
(46, 'Otros', 5),
(47, 'Otros', 1),
(48, 'Otros', 3),
(49, 'Salario', 2),
(50, 'Salario', 4),
(51, 'Salario', 5),
(52, 'Salario', 1),
(53, 'Salario', 3),
(54, 'Deporte', 2),
(55, 'Deporte', 4),
(56, 'Deporte', 5),
(57, 'Deporte', 1),
(58, 'Deporte', 3),
(77, 'Compra', 7),
(78, 'Comida', 7),
(79, 'Ocio', 7),
(80, 'Ropa', 7),
(81, 'Salud', 7),
(82, 'Transporte', 7),
(83, 'Otros', 7),
(84, 'Salario', 7),
(85, 'Deporte', 7),
(86, 'DFSF', 7),
(87, 'Compra', 8),
(88, 'Comida', 8),
(89, 'Ocio', 8),
(90, 'Ropa', 8),
(91, 'Salud', 8),
(92, 'Transporte', 8),
(93, 'Otros', 8),
(94, 'Salario', 8),
(95, 'Deporte', 8),
(96, 'dsa', 8);

--
-- 转存表中的数据 `gastos`
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
(25, 1, 'Gasto', 4, 54.00, '2025-03-07', ''),
(27, 2, 'Ingreso', 4, 3.00, '2025-03-08', ''),
(28, 2, 'Ingreso', 2, 1.00, '2025-03-31', ''),
(29, 2, 'Ingreso', 9, 45.00, '2025-03-31', ''),
(31, 2, 'Gasto', 5, 1.00, '2025-04-01', ''),
(32, 2, 'Gasto', 5, 23.00, '2025-04-01', ''),
(33, 2, 'Gasto', 4, 23.00, '2025-04-01', ''),
(37, 4, 'Gasto', 1, 23.00, '2025-04-02', ''),
(38, 4, 'Gasto', 2, 23.00, '2025-04-11', ''),
(42, 4, 'Gasto', 2, 23.00, '2323-02-23', ''),
(43, 4, 'Ingreso', 10, 33.00, '2025-04-02', ''),
(44, 4, 'Gasto', 1, 2.00, '2025-04-02', ''),
(45, 4, 'Gasto', 10, 3.00, '3232-04-02', ''),
(46, 4, 'Ingreso', 11, 32.00, '2025-04-02', ''),
(47, 4, 'Ingreso', 5, 32.00, '2025-04-02', ''),
(48, 4, 'Gasto', 10, 22.00, '2025-04-02', ''),
(49, 2, 'Gasto', 4, 3.00, '2025-04-02', ''),
(50, 2, 'Gasto', 5, 32.00, '2025-04-03', ''),
(51, 2, 'Ingreso', 12, 23.00, '2025-04-03', ''),
(53, 2, 'Ingreso', 5, 4.00, '2025-04-01', '32'),
(54, 2, 'Gasto', 4, 4.00, '2025-04-02', ''),
(55, 2, 'Ingreso', 13, 55.00, '2025-04-03', 'dsff222'),
(57, 2, 'Ingreso', 13, 23.00, '2025-03-31', 'rear'),
(58, 2, 'Ingreso', 5, 23.00, '2025-04-02', 'dasd'),
(60, 7, 'Gasto', 78, 32.00, '2025-04-07', 'Hammbusd'),
(61, 7, 'Ingreso', 84, 222.00, '2025-04-07', ''),
(62, 7, 'Ingreso', 86, 223.00, '2025-04-08', ''),
(63, 7, 'Gasto', 78, 90.00, '2025-04-07', ''),
(64, 7, 'Ingreso', 84, 400.00, '2025-03-12', ''),
(65, 7, 'Gasto', 85, 120.00, '2025-02-06', ''),
(66, 8, 'Gasto', 88, 223.00, '2025-04-09', ''),
(67, 8, 'Ingreso', 87, 23.00, '2025-04-07', ''),
(68, 8, 'Ingreso', 94, 2323.00, '2025-03-14', ''),
(69, 8, 'Ingreso', 96, 23.00, '2025-01-10', ''),
(70, 8, 'Gasto', 96, 2323.00, '2025-03-13', ''),
(71, 2, 'Gasto', 14, 23.00, '2025-04-07', '');

--
-- 转存表中的数据 `grupos`
--

INSERT INTO `grupos` (`id`, `nombre`, `descripcion`, `objetivo`, `fecha_creacion`) VALUES
(1, 'Viaje Grecia', 'dfs', 344.00, '2025-04-06');

--
-- 转存表中的数据 `grupo_usuarios`
--

INSERT INTO `grupo_usuarios` (`grupo_id`, `usuario_id`, `rol_grupo`) VALUES
(1, 2, '');

--
-- 转存表中的数据 `usuarios`
--

 --
 -- Volcado de datos para la tabla `usuarios`
 --
 
INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`) VALUES
(1, 'Ejemplo', 'ejemplo@correo.com', 'claveEncriptada', 'usuario'),
(2, '1', '1@gmail.com', '$2y$10$8075aKGizIX/pNRBeaPBleF8Ely1aO/lhb6WwVFfPNXmDxfIvWivK', 'usuario'),
(3, 'Pepe', 'yundie@gmail.com', '$2y$10$Sm17eZX8jgrzmJKdVj3QhOMlsQALu6/eYvFsO0RlgwkZUt3l5T0.q', 'usuario'),
(4, '2', '2@gmail.com', '$2y$10$PQjt/gQp7ce0guX3jp8vhepN0ZF.jSpQTL63oyoZGpt9ILaFALjsy', 'usuario'),
(5, '3', '3@gmail.com', '$2y$10$9uxLwQjwPIMePMN5Fgrmlelv00zU3KQ9SuWOe/hoPTwTtMvXZN/Uu', 'usuario'),
(6, '11', '11@gmail.com', '$2y$10$9.qhGJlVxrWRNfiiJYuNkeWGh7WRgfdL875lEwknqhR6cU0xllsdi', 'usuario'),
(7, '22', '22@gmail.com', '$2y$10$M8pCMq6e1u9sVZGyjfcnzeuy2qV8yxUYycKCoUpae.jqZh3HNnbv2', 'usuario'),
(8, '33', '33@gmail.com', '$2y$10$qnO6ov4mprV0S.Om/1HbaeMQazGuIyrcrWoGPzZD1GMyITTsDgrT6', 'usuario');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
