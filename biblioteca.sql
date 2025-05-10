
-- Crear tabla de roles
CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255),
    nivel_acceso INT NOT NULL DEFAULT 1,
    created_at INT NOT NULL,
    updated_at INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla de permisos
CREATE TABLE permisos (
    id_permiso INT AUTO_INCREMENT PRIMARY KEY,
    nombre_permiso VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    created_at INT NOT NULL,
    updated_at INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla pivote roles_permisos
CREATE TABLE roles_permisos (
    id_rol_permiso INT AUTO_INCREMENT PRIMARY KEY,
    id_rol INT NOT NULL,
    id_permiso INT NOT NULL,
    created_at INT NOT NULL,
    UNIQUE KEY unique_rol_permiso (id_rol, id_permiso),
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_permiso) REFERENCES permisos(id_permiso) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla de usuarios normalizada
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100),
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    auth_key VARCHAR(32),
    access_token VARCHAR(255),
    id_rol INT NOT NULL,
    status SMALLINT NOT NULL DEFAULT 10,
    imagen_perfil VARCHAR(255),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at INT NOT NULL,
    updated_at INT NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla para autenticación externa
CREATE TABLE autenticacion_externa (
    id_auth_externa INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    proveedor VARCHAR(50) NOT NULL,
    proveedor_id VARCHAR(255) NOT NULL,
    token_acceso VARCHAR(255),
    ultimo_acceso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_provider_id (proveedor, proveedor_id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla para usuarios estudiantes
CREATE TABLE usuarios_estudiantes (
    id_estudiante INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL UNIQUE,
    carnet VARCHAR(20) UNIQUE,
    carrera VARCHAR(100),
    semestre INT,
    created_at INT NOT NULL,
    updated_at INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla para usuarios profesores
CREATE TABLE usuarios_profesores (
    id_profesor INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL UNIQUE,
    especialidad VARCHAR(100),
    departamento VARCHAR(100),
    oficina VARCHAR(50),
    created_at INT NOT NULL,
    updated_at INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla para personal administrativo
CREATE TABLE usuarios_personal (
    id_personal INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL UNIQUE,
    departamento VARCHAR(100),
    cargo VARCHAR(100),
    fecha_contratacion DATE,
    created_at INT NOT NULL,
    updated_at INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: autores
CREATE TABLE autores (
    id_autor INT AUTO_INCREMENT PRIMARY KEY,
    nombre_autor VARCHAR(100),
    imagen_autor VARCHAR(255),
    nacionalidad VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: categorias
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: libros
CREATE TABLE libros (
    id_libro INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150),
    imagen_portada VARCHAR(255),
    id_autor INT,
    id_categoria INT,
    anio_publicacion YEAR,
    disponible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_autor) REFERENCES autores(id_autor),
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: prestamos
CREATE TABLE prestamos (
    id_prestamo INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_libro INT,
    fecha_prestamo DATE,
    fecha_devolucion DATE,
    devuelto BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_libro) REFERENCES libros(id_libro)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar roles básicos
INSERT INTO roles (nombre_rol, descripcion, nivel_acceso, created_at, updated_at) VALUES
('admin', 'Administrador del sistema con acceso completo', 100, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('bibliotecario', 'Personal de biblioteca con acceso a gestión de libros y préstamos', 50, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('profesor', 'Profesores con privilegios extendidos', 30, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('estudiante', 'Estudiantes con acceso básico', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('invitado', 'Usuarios con acceso de solo lectura', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- Insertar permisos básicos
INSERT INTO permisos (nombre_permiso, descripcion, created_at, updated_at) VALUES
('administrar_usuarios', 'Gestionar todos los usuarios del sistema', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('administrar_libros', 'Gestionar catálogo de libros', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('administrar_prestamos', 'Gestionar préstamos y devoluciones', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('realizar_prestamos', 'Solicitar préstamos de libros', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('ver_catalogo', 'Visualizar el catálogo de libros', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- Asignar permisos a roles
INSERT INTO roles_permisos (id_rol, id_permiso, created_at) VALUES
-- Admin tiene todos los permisos
(1, 1, UNIX_TIMESTAMP()), -- administrar usuarios
(1, 2, UNIX_TIMESTAMP()), -- administrar libros
(1, 3, UNIX_TIMESTAMP()), -- administrar préstamos
(1, 4, UNIX_TIMESTAMP()), -- realizar préstamos
(1, 5, UNIX_TIMESTAMP()), -- ver catálogo

-- Bibliotecario
(2, 2, UNIX_TIMESTAMP()), -- administrar libros
(2, 3, UNIX_TIMESTAMP()), -- administrar préstamos
(2, 4, UNIX_TIMESTAMP()), -- realizar préstamos
(2, 5, UNIX_TIMESTAMP()), -- ver catálogo

-- Profesor
(3, 4, UNIX_TIMESTAMP()), -- realizar préstamos
(3, 5, UNIX_TIMESTAMP()), -- ver catálogo

-- Estudiante
(4, 4, UNIX_TIMESTAMP()), -- realizar préstamos
(4, 5, UNIX_TIMESTAMP()), -- ver catálogo

-- Invitado
(5, 5, UNIX_TIMESTAMP()); -- ver catálogo

-- Insertar usuarios iniciales
INSERT INTO usuarios (username, nombre, apellidos, email, password_hash, auth_key, id_rol, status, created_at, updated_at) VALUES
('ana.torres', 'Ana', 'Torres', 'ana@example.com', 
 '$2y$13$A2bhNRR1Hkz.pkKuTUQYLODzFHGQcqpf7MjVtLVLCNOxG/z/VmT/K', 
 MD5(CONCAT('ana.torres', RAND())), 4, 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('carlos.ruiz', 'Carlos', 'Ruiz', 'carlos@example.com', 
 '$2y$13$A2bhNRR1Hkz.pkKuTUQYLODzFHGQcqpf7MjVtLVLCNOxG/z/VmT/K', 
 MD5(CONCAT('carlos.ruiz', RAND())), 4, 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('laura.gomez', 'Laura', 'Gómez', 'laura@example.com', 
 '$2y$13$A2bhNRR1Hkz.pkKuTUQYLODzFHGQcqpf7MjVtLVLCNOxG/z/VmT/K', 
 MD5(CONCAT('laura.gomez', RAND())), 4, 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('mario.velez', 'Mario', 'Vélez', 'mario@example.com', 
 '$2y$13$A2bhNRR1Hkz.pkKuTUQYLODzFHGQcqpf7MjVtLVLCNOxG/z/VmT/K', 
 MD5(CONCAT('mario.velez', RAND())), 4, 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('elena.paredes', 'Elena', 'Paredes', 'elena@example.com', 
 '$2y$13$A2bhNRR1Hkz.pkKuTUQYLODzFHGQcqpf7MjVtLVLCNOxG/z/VmT/K', 
 MD5(CONCAT('elena.paredes', RAND())), 4, 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- Autores
INSERT INTO autores (nombre_autor, nacionalidad) VALUES
('Gabriel García Márquez', 'Colombiano'),
('Isabel Allende', 'Chilena'),
('Mario Vargas Llosa', 'Peruano'),
('J. K. Rowling', 'Británica'),
('George Orwell', 'Británico');

-- Categorías
INSERT INTO categorias (nombre_categoria) VALUES
('Novela'),
('Fantasía'),
('Ciencia Ficción'),
('Ensayo'),
('Clásicos');

-- Libros
INSERT INTO libros (titulo, id_autor, id_categoria, anio_publicacion) VALUES
('Cien años de soledad', 1, 1, 1967),
('La casa de los espíritus', 2, 1, 1982),
('Conversación en La Catedral', 3, 1, 1969),
('1984', 5, 3, 1949),
('Harry Potter y la piedra filosofal', 4, 2, 1997);

-- Préstamos
INSERT INTO prestamos (id_usuario, id_libro, fecha_prestamo, fecha_devolucion, devuelto) VALUES
(1, 1, '2025-04-01', '2025-04-15', TRUE),
(2, 2, '2025-04-05', '2025-04-19', TRUE),
(3, 3, '2025-04-10', '2025-04-24', FALSE),
(4, 4, '2025-04-15', '2025-04-29', FALSE),
(5, 5, '2025-04-17', '2025-05-01', FALSE);

-- Insertar un ejemplo de estudiante
INSERT INTO usuarios (username, nombre, apellidos, email, password_hash, auth_key, id_rol, status, created_at, updated_at)
VALUES ('estudiante.ejemplo', 'Estudiante', 'Ejemplo', 'estudiante@example.com', 
       '$2y$13$A2bhNRR1Hkz.pkKuTUQYLODzFHGQcqpf7MjVtLVLCNOxG/z/VmT/K', 
       MD5(CONCAT('estudiante.ejemplo', RAND())), 4, 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO usuarios_estudiantes (id_usuario, carnet, carrera, semestre, created_at, updated_at)
VALUES (LAST_INSERT_ID(), 'EST-2025-001', 'Ingeniería en Sistemas', 5, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- Insertar un ejemplo de profesor
INSERT INTO usuarios (username, nombre, apellidos, email, password_hash, auth_key, id_rol, status, created_at, updated_at)
VALUES ('profesor.ejemplo', 'Profesor', 'Ejemplo', 'profesor@example.com', 
       '$2y$13$A2bhNRR1Hkz.pkKuTUQYLODzFHGQcqpf7MjVtLVLCNOxG/z/VmT/K', 
       MD5(CONCAT('profesor.ejemplo', RAND())), 3, 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO usuarios_profesores (id_usuario, especialidad, departamento, oficina, created_at, updated_at)
VALUES (LAST_INSERT_ID(), 'Literatura Latinoamericana', 'Humanidades', 'H-201', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- Insertar un ejemplo de personal
INSERT INTO usuarios (username, nombre, apellidos, email, password_hash, auth_key, id_rol, status, created_at, updated_at)
VALUES ('personal.ejemplo', 'Personal', 'Ejemplo', 'personal@example.com', 
       '$2y$13$A2bhNRR1Hkz.pkKuTUQYLODzFHGQcqpf7MjVtLVLCNOxG/z/VmT/K', 
       MD5(CONCAT('personal.ejemplo', RAND())), 2, 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO usuarios_personal (id_usuario, departamento, cargo, fecha_contratacion, created_at, updated_at)
VALUES (LAST_INSERT_ID(), 'Biblioteca', 'Bibliotecario', '2024-01-15', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());