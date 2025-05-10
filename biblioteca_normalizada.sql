-- Estructura de base de datos normalizada para el sistema de biblioteca
-- Creado para la aplicación Yii2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Base de datos: `biblioteca`
-- --------------------------------------------------------

-- --------------------------------------------------------
-- Tabla `roles`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `nivel_acceso` int(11) NOT NULL DEFAULT 0,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `nombre_rol` (`nombre_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Datos iniciales para la tabla `roles`
INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`, `nivel_acceso`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrador del sistema con acceso completo', 100, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(2, 'bibliotecario', 'Encargado de la biblioteca con acceso a la gestión de libros y préstamos', 80, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(3, 'profesor', 'Profesor con acceso a reservas y préstamos extendidos', 60, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(4, 'estudiante', 'Estudiante con acceso básico para préstamos', 40, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(5, 'invitado', 'Usuario invitado con acceso de solo lectura', 20, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- --------------------------------------------------------
-- Tabla `permisos`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `permisos` (
  `id_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_permiso` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id_permiso`),
  UNIQUE KEY `nombre_permiso` (`nombre_permiso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Datos iniciales para la tabla `permisos`
INSERT INTO `permisos` (`id_permiso`, `nombre_permiso`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'administrar_usuarios', 'Crear, editar y eliminar usuarios', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(2, 'administrar_roles', 'Crear, editar y eliminar roles', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(3, 'administrar_libros', 'Crear, editar y eliminar libros', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(4, 'gestionar_prestamos', 'Aprobar y gestionar préstamos', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(5, 'solicitar_prestamos', 'Solicitar préstamos de libros', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(6, 'ver_catalogo', 'Ver el catálogo de libros', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(7, 'generar_reportes', 'Generar reportes del sistema', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(8, 'reservar_libros', 'Reservar libros antes de préstamo', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- --------------------------------------------------------
-- Tabla `roles_permisos`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles_permisos` (
  `id_rol_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id_rol_permiso`),
  UNIQUE KEY `id_rol_id_permiso` (`id_rol`,`id_permiso`),
  KEY `id_permiso` (`id_permiso`),
  CONSTRAINT `fk_rol_permiso_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE,
  CONSTRAINT `fk_rol_permiso_permiso` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Asignación inicial de permisos a roles
INSERT INTO `roles_permisos` (`id_rol`, `id_permiso`, `created_at`) VALUES
-- Admin tiene todos los permisos
(1, 1, UNIX_TIMESTAMP()),
(1, 2, UNIX_TIMESTAMP()),
(1, 3, UNIX_TIMESTAMP()),
(1, 4, UNIX_TIMESTAMP()),
(1, 5, UNIX_TIMESTAMP()),
(1, 6, UNIX_TIMESTAMP()),
(1, 7, UNIX_TIMESTAMP()),
(1, 8, UNIX_TIMESTAMP()),
-- Bibliotecario
(2, 3, UNIX_TIMESTAMP()),
(2, 4, UNIX_TIMESTAMP()),
(2, 5, UNIX_TIMESTAMP()),
(2, 6, UNIX_TIMESTAMP()),
(2, 7, UNIX_TIMESTAMP()),
(2, 8, UNIX_TIMESTAMP()),
-- Profesor
(3, 5, UNIX_TIMESTAMP()),
(3, 6, UNIX_TIMESTAMP()),
(3, 8, UNIX_TIMESTAMP()),
-- Estudiante
(3, 5, UNIX_TIMESTAMP()),
(3, 6, UNIX_TIMESTAMP()),
-- Invitado
(5, 6, UNIX_TIMESTAMP());

-- --------------------------------------------------------
-- Tabla `usuarios` (Sistema de autenticación)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `auth_key` varchar(32) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `id_rol` int(11) NOT NULL DEFAULT 4,
  `status` tinyint(1) NOT NULL DEFAULT 10,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`),
  KEY `id_rol` (`id_rol`),
  CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Datos iniciales para la tabla `usuarios` (admin password: admin123)
INSERT INTO `usuarios` (`id_usuario`, `username`, `password_hash`, `email`, `nombre`, `apellido`, `auth_key`, `id_rol`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$13$BSG1m.jomL9C5o3hzYNQrexBQHPodFZBEn.7JYG5J1SaakYV/Hqhq', 'admin@biblioteca.com', 'Administrador', 'Sistema', '12345678901234567890123456789012', 1, 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(2, 'bibliotecario', '$2y$13$BSG1m.jomL9C5o3hzYNQrexBQHPodFZBEn.7JYG5J1SaakYV/Hqhq', 'biblio@biblioteca.com', 'Bibliotecario', 'Principal', '23456789012345678901234567890123', 2, 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(3, 'profesor', '$2y$13$BSG1m.jomL9C5o3hzYNQrexBQHPodFZBEn.7JYG5J1SaakYV/Hqhq', 'profesor@universidad.edu', 'Profesor', 'Ejemplo', '34567890123456789012345678901234', 3, 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(4, 'estudiante', '$2y$13$BSG1m.jomL9C5o3hzYNQrexBQHPodFZBEn.7JYG5J1SaakYV/Hqhq', 'estudiante@universidad.edu', 'Estudiante', 'Ejemplo', '45678901234567890123456789012345', 4, 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- --------------------------------------------------------
-- Tabla `autenticacion_externa`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `autenticacion_externa` (
  `id_auth_externa` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `proveedor` varchar(50) NOT NULL,
  `proveedor_id` varchar(255) NOT NULL,
  `token_acceso` varchar(255) DEFAULT NULL,
  `ultimo_acceso` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_auth_externa`),
  UNIQUE KEY `proveedor_proveedor_id` (`proveedor`,`proveedor_id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `fk_auth_externa_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabla `usuarios_estudiantes`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios_estudiantes` (
  `id_estudiante` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `carnet` varchar(20) DEFAULT NULL,
  `carrera` varchar(100) DEFAULT NULL,
  `semestre` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id_estudiante`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  UNIQUE KEY `carnet` (`carnet`),
  CONSTRAINT `fk_estudiante_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabla `usuarios_profesores`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios_profesores` (
  `id_profesor` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `oficina` varchar(50) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id_profesor`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `fk_profesor_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabla `usuarios_personal`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios_personal` (
  `id_personal` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `fecha_contratacion` date DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id_personal`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `fk_personal_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Actualizar tablas existentes
-- --------------------------------------------------------

-- Modificar la tabla 'libros' si existe para mantener consistencia
ALTER TABLE `libros` ADD COLUMN IF NOT EXISTS `updated_at` int(11) DEFAULT NULL AFTER `created_at`;

-- Modificar la tabla 'prestamos' si existe para mantener consistencia
ALTER TABLE `prestamos` ADD COLUMN IF NOT EXISTS `updated_at` int(11) DEFAULT NULL AFTER `created_at`;

-- --------------------------------------------------------
-- Migración de datos de usuarios existentes (si aplica)
-- --------------------------------------------------------

-- Esta sección debe ejecutarse manualmente después de revisar la estructura actual de la base de datos.
-- La idea es migrar los usuarios existentes de la tabla user antigua a la nueva estructura.

/*
-- Ejemplo de migración:
INSERT INTO usuarios (username, password_hash, email, nombre, apellido, auth_key, status, created_at, updated_at, id_rol)
SELECT username, password_hash, email, nombre, apellido, auth_key, status, created_at, updated_at, 4
FROM user;
*/
