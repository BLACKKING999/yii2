-- Script para ampliar la tabla libros con todos los campos necesarios

-- Añadir columna para almacenar descripciones
ALTER TABLE libros ADD COLUMN IF NOT EXISTS descripcion TEXT NULL AFTER disponible;

-- Añadir columna para ISBN
ALTER TABLE libros ADD COLUMN IF NOT EXISTS isbn VARCHAR(20) NULL AFTER id_libro;

-- Añadir columna para editorial
ALTER TABLE libros ADD COLUMN IF NOT EXISTS editorial VARCHAR(100) NULL AFTER id_categoria;

-- Añadir columna para número de páginas
ALTER TABLE libros ADD COLUMN IF NOT EXISTS num_paginas INT NULL AFTER anio_publicacion;

-- Añadir columna para idioma
ALTER TABLE libros ADD COLUMN IF NOT EXISTS idioma VARCHAR(50) NULL AFTER num_paginas;

-- Añadir columna para ubicación física
ALTER TABLE libros ADD COLUMN IF NOT EXISTS ubicacion_fisica VARCHAR(100) NULL AFTER idioma;

-- Añadir columnas para timestamps (created_at, updated_at)
ALTER TABLE libros ADD COLUMN IF NOT EXISTS created_at INT NULL AFTER ubicacion_fisica;
ALTER TABLE libros ADD COLUMN IF NOT EXISTS updated_at INT NULL AFTER created_at;

-- Añadir descripción para el libro 1984 de George Orwell
UPDATE libros SET 
    descripcion = '1984 es una novela distópica escrita por George Orwell que presenta una sociedad totalitaria donde el gobierno, conocido como "El Gran Hermano", ejerce un control absoluto sobre sus ciudadanos. La historia sigue a Winston Smith, un trabajador que comienza a cuestionar el régimen y busca rebelarse contra él. La novela explora temas como la vigilancia gubernamental, el lavado de cerebro, la manipulación de la verdad y la represión de la individualidad. Publicada en 1949, la obra se ha convertido en un referente de la literatura distópica y una poderosa advertencia sobre los peligros del totalitarismo.',
    isbn = '978-0451524935',
    editorial = 'Signet Classic',
    num_paginas = 328,
    idioma = 'Inglés',
    ubicacion_fisica = 'Estantería A, Sección 3',
    created_at = UNIX_TIMESTAMP(),
    updated_at = UNIX_TIMESTAMP()
WHERE titulo LIKE '%1984%' AND id_autor IN (SELECT id_autor FROM autores WHERE nombre_autor LIKE '%Orwell%');
