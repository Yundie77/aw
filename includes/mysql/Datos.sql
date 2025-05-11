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


-- Primero se insertan los usuarios para evitar problemas de claves foráneas con categorias

-- Categorías
INSERT INTO `categorias` (`id`, `nombre`, `usuario_id`) VALUES
(1, 'Compra', 0),
(2, 'Comida', 0),
(3, 'Ocio', 0),
(4, 'Ropa', 0),
(5, 'Salud', 0),
(6, 'Transporte', 0),
(7, 'Otros', 0),
(8, 'Salario', 0),
... -- (continúan hasta id=153, como en tu volcado original)

-- Usuarios
INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `estado`, `bloqueado_hasta`, `fecha_creacion`) VALUES
(1, 'admin', 'admin@gmail.com', '...', 'admin', 'activo', NULL, '2025-01-11 22:52:56'),
(2, 'Yundie', 'yundie@gmail.com', '...', 'usuario', 'activo', NULL, '2025-05-11 22:54:23'),
(3, 'Junhua', 'junhua@gmail.com', '...', 'usuario', 'inactivo', NULL, '2025-04-11 22:54:47'),
(4, 'Vahram', 'vahram@gmail.com', '...', 'usuario', 'activo', NULL, '2025-05-11 22:55:10'),
(5, 'Hua', 'hua@gmail.com', '...', 'usuario', 'activo', NULL, '2025-03-11 22:55:31'),
(6, 'Natalia', 'natalia@gmail.com', '...', 'usuario', 'activo', NULL, '2025-03-11 22:55:55'),
(7, 'Javier', 'javier@gmail.com', '...', 'usuario', 'activo', NULL, '2025-05-11 22:56:59'),
(8, 'Eva', 'eva@gmail.com', '...', 'usuario', 'activo', NULL, '2025-04-11 22:57:16'),
(9, 'admin2', 'admin2@gmail.com', '...', 'admin', 'activo', NULL, '2025-02-11 22:57:41');

-- Grupos
INSERT INTO `grupos` (`id`, `nombre`, `objetivo`, `fecha_creacion`) VALUES
(1, 'NUEVAYol 25\'', 1000.00, '2025-05-11'),
(2, 'Viaje Madrid', 235.00, '2025-05-11'),
(3, 'Comida Fin de Curso', 60.00, '2025-05-11'),
(4, 'Nos vamos a Bali', 2800.00, '2025-05-11'),
(5, 'Asador Argentino', 198.00, '2025-05-11'),
(6, 'Despedida de Soltero', 950.00, '2025-05-11');

-- Grupo_Usuarios
INSERT INTO `grupo_usuarios` (`grupo_id`, `usuario_id`, `rol_grupo`) VALUES
(1, 2, 'miembro'),
(1, 4, 'miembro'),
(1, 6, 'admin_grupo'),
(1, 7, 'admin_grupo'),
(2, 6, 'admin_grupo'),
(2, 7, 'miembro'),
(2, 8, 'miembro'),
(3, 2, 'miembro'),
(3, 3, 'miembro'),
(3, 4, 'miembro'),
(3, 5, 'miembro'),
(3, 6, 'admin_grupo'),
(3, 7, 'miembro'),
(3, 8, 'miembro'),
(4, 5, 'miembro'),
(4, 7, 'admin_grupo'),
(5, 2, 'miembro'),
(5, 3, 'admin_grupo'),
(5, 4, 'miembro'),
(5, 7, 'miembro'),
(6, 2, 'miembro'),
(6, 3, 'miembro'),
(6, 5, 'admin_grupo'),
(6, 7, 'miembro');

-- Gastos
INSERT INTO `gastos` (`id`, `usuario_id`, `tipo`, `categoria_id`, `monto`, `fecha`, `comentario`) VALUES
(1, 6, 'Ingreso', 52, 2500.00, '2025-05-01', 'Salario de mayo'),
(2, 6, 'Ingreso', 52, 200.00, '2025-05-05', 'Bono por productividad'),
(3, 6, 'Gasto', 2, 50.00, '2025-05-02', 'Cena en restaurante'),
... -- (continúan hasta id=49)

-- Gastos grupales
INSERT INTO `gastos_grupales` (`id`, `grupo_id`, `usuario_id`, `monto`, `fecha`, `comentario`) VALUES
(1, 3, 7, 150.00, '2025-05-01', 'Comida restaurante'),
(2, 3, 6, 80.00, '2025-05-11', 'Gin Tonics'),
(3, 1, 2, 75.50, '2025-05-05', 'Cena en Times Square, Nueva York'),
... -- (continúan hasta id=38)

-- Mensajes
INSERT INTO `mensajes` (`id`, `usuario_id`, `grupo_id`, `contenido`, `fecha`) VALUES
(1, 7, 3, 'Chicos, acabo de meter el total de la comida. He pagado yo lo de todos. Pero esta vez no invito ;)', '2025-05-11 23:20:03'),
(2, 6, 3, 'Genial! Yo acabo de meter lo que costaron los Gin Tonics de después ejjeje', '2025-05-11 23:21:02'),
... -- (continúan hasta id=32)
