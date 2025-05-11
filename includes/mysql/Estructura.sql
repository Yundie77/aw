-- Configuración inicial
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Codificación
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
 /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
 /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 /*!40101 SET NAMES utf8mb4 */;

-- Base de datos: `awp2`

-- Tabla: categorias
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: gastos
CREATE TABLE `gastos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('Ingreso','Gasto') NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: gastos_grupales
CREATE TABLE `gastos_grupales` (
  `id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: grupos
CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `objetivo` decimal(10,2) DEFAULT 0.00,
  `fecha_creacion` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: grupo_usuarios
CREATE TABLE `grupo_usuarios` (
  `grupo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `rol_grupo` enum('miembro','admin_grupo') DEFAULT 'miembro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: mensajes
CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `contenido` text NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: usuarios
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('usuario','admin') NOT NULL DEFAULT 'usuario',
  `estado` enum('activo','inactivo','bloqueado') NOT NULL DEFAULT 'activo',
  `bloqueado_hasta` datetime DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Índices
ALTER TABLE `categorias` ADD PRIMARY KEY (`id`);
ALTER TABLE `gastos` ADD PRIMARY KEY (`id`), ADD KEY `usuario_id` (`usuario_id`), ADD KEY `categoria_id` (`categoria_id`);
ALTER TABLE `gastos_grupales` ADD PRIMARY KEY (`id`), ADD KEY `grupo_id` (`grupo_id`), ADD KEY `usuario_id` (`usuario_id`);
ALTER TABLE `grupos` ADD PRIMARY KEY (`id`);
ALTER TABLE `grupo_usuarios` ADD PRIMARY KEY (`grupo_id`,`usuario_id`), ADD KEY `usuario_id` (`usuario_id`);
ALTER TABLE `mensajes` ADD PRIMARY KEY (`id`), ADD KEY `usuario_id` (`usuario_id`), ADD KEY `grupo_id` (`grupo_id`);
ALTER TABLE `usuarios` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

-- AUTO_INCREMENT
ALTER TABLE `categorias` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;
ALTER TABLE `gastos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
ALTER TABLE `gastos_grupales` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
ALTER TABLE `grupos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `mensajes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
ALTER TABLE `usuarios` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

-- Claves foráneas
ALTER TABLE `gastos` ADD CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `gastos` ADD CONSTRAINT `gastos_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE;

ALTER TABLE `gastos_grupales` ADD CONSTRAINT `gastos_grupales_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `gastos_grupales` ADD CONSTRAINT `gastos_grupales_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `grupo_usuarios` ADD CONSTRAINT `grupo_usuarios_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `grupo_usuarios` ADD CONSTRAINT `grupo_usuarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mensajes` ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `mensajes` ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;
