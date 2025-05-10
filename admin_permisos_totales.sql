-- Script para otorgar control total al administrador en el sistema de biblioteca
-- Creado: 2025-05-09

-- 1. Crear nuevos permisos para control total del sistema
INSERT INTO `permisos` (`nombre_permiso`, `descripcion`, `created_at`, `updated_at`) 
VALUES 
('acceso_total_sistema', 'Acceso sin restricciones a todas las funcionalidades del sistema', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('gestionar_config_sistema', 'Gestionar configuración global del sistema', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('modificar_estructura_bd', 'Acceso para realizar modificaciones a la estructura de la base de datos', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('ver_todas_vistas', 'Ver todas las vistas y páginas del sistema sin restricciones', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('crear_usuarios', 'Crear nuevos usuarios en el sistema', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('modificar_usuarios', 'Modificar datos de cualquier usuario', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('eliminar_usuarios', 'Eliminar usuarios del sistema', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('asignar_roles', 'Asignar roles a cualquier usuario', UNIX_TIMESTAMP(), UNIX_TIMESTAMP())
ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion), updated_at = UNIX_TIMESTAMP();

-- 2. Actualizar el rol de admin para que sea definitivamente 'Administrador'
UPDATE `roles` SET `nombre_rol` = 'Administrador', `descripcion` = 'Administrador del sistema con control total y acceso ilimitado a todas las funcionalidades', 
`nivel_acceso` = 100, `updated_at` = UNIX_TIMESTAMP() 
WHERE `id_rol` = 1 OR `nombre_rol` = 'admin';

-- 3. Asegurarse de que todos los permisos estén asignados al rol de administrador
INSERT INTO `roles_permisos` (`id_rol`, `id_permiso`, `created_at`)
SELECT 1, `id_permiso`, UNIX_TIMESTAMP() FROM `permisos`
ON DUPLICATE KEY UPDATE `created_at` = UNIX_TIMESTAMP();

-- 4. Asegurarse de que el primer usuario sea administrador
UPDATE `usuarios` SET `id_rol` = 1 WHERE `id_usuario` = 1;

-- 5. Este trigger asegura que cualquier permiso nuevo creado en el futuro se asigne automáticamente al administrador
DELIMITER //
DROP TRIGGER IF EXISTS AsignarPermisosAdmin //
CREATE TRIGGER AsignarPermisosAdmin AFTER INSERT ON `permisos`
FOR EACH ROW
BEGIN
    INSERT IGNORE INTO `roles_permisos` (`id_rol`, `id_permiso`, `created_at`)
    VALUES (1, NEW.id_permiso, UNIX_TIMESTAMP());
END //
DELIMITER ;

-- Mensaje de confirmación
SELECT 'Control total otorgado al rol de Administrador' AS Resultado;
