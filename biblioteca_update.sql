-- Usar la base de datos
USE biblioteca_virtual;

-- Añadir campo de imagen a la tabla usuarios
ALTER TABLE usuarios 
ADD COLUMN imagen_perfil VARCHAR(255) NULL AFTER contrasena;

-- Añadir campo de imagen a la tabla autores
ALTER TABLE autores 
ADD COLUMN imagen_autor VARCHAR(255) NULL AFTER nombre_autor;

-- Añadir campo de imagen a la tabla libros
ALTER TABLE libros 
ADD COLUMN imagen_portada VARCHAR(255) NULL AFTER titulo;

-- Añadir campo para autenticación con Google
ALTER TABLE usuarios 
ADD COLUMN google_id VARCHAR(255) NULL AFTER correo,
ADD COLUMN es_google BOOLEAN DEFAULT FALSE AFTER google_id;
