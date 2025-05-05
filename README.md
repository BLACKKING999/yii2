<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://github.com/BLACKKING999/yii/blob/main/Captura%20de%20pantalla%202025-04-22%20194812.png?raw=true">
    </a>
    <h1 align="center">Biblioteca Virtual</h1>
    <br>
</p>


Sistema de Gestión de Biblioteca
================================

Este proyecto es una aplicación web desarrollada con Yii 2 para la gestión integral de una biblioteca. Permite administrar libros, autores, categorías y préstamos de manera simple y eficaz.

Características Principales
---------------------------
- **Gestión de Libros:** Registro, edición, eliminación y consulta de libros.
- **Gestión de Autores:** Alta y administración de autores.
- **Gestión de Categorías:** Organización de libros por categorías temáticas.
- **Gestión de Préstamos:** Control de préstamos y devoluciones de libros.

Estructura del Proyecto
------------------------
- `models/` - Modelos de datos (Libro, Autor, Categoría, Préstamo, etc.)
- `controllers/` - Controladores que manejan la lógica de negocio
- `views/` - Vistas para interacción con el usuario
- `config/` - Configuración general de la aplicación

Requisitos
----------
- PHP >= 7.4
- Servidor web (Apache, Nginx, etc.)
- Composer

Instalación Rápida
------------------
1. Clona o descarga este repositorio.
2. Instala las dependencias con Composer:
   ```bash
   composer install
   ```
3. Configura tu base de datos en `config/db.php`.
4. Aplica las migraciones necesarias:
   ```bash
   php yii migrate
   ```
5. Inicia el servidor de desarrollo:
   ```bash
   php yii serve
   ```
6. Accede a la aplicación en `http://localhost:8080`.

Badges
------
[![Última versión estable](https://img.shields.io/packagist/v/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Descargas totales](https://img.shields.io/packagist/dt/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Compilación](https://github.com/yiisoft/yii2-app-basic/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-basic/actions?query=workflow%3Abuild)

Estructura de Directorios
--------------------------
```
assets/             Definición de recursos (CSS, JS)
commands/           Comandos de consola
config/             Configuraciones de la aplicación
controllers/        Controladores web
mail/               Vistas para correos electrónicos
models/             Modelos de datos
runtime/            Archivos generados en tiempo de ejecución
tests/              Pruebas automatizadas
vendor/             Dependencias externas
views/              Vistas web
web/                Recursos públicos y script de entrada
```

Instalación
-----------
### Vía Composer
Si no tienes Composer, sigue las instrucciones en [getcomposer.org](https://getcomposer.org/).

Instala el proyecto con:
```
composer create-project --prefer-dist yiisoft/yii2-app-basic basic
```
Accede a la aplicación desde:
```
http://localhost/basic/web/
```

### Desde un archivo comprimido
Extrae el archivo descargado de [yiiframework.com](https://www.yiiframework.com/download/) en una carpeta `basic` dentro del root del servidor web.

Agrega una clave de validación de cookies en `config/web.php`:
```php
'request' => [
    'cookieValidationKey' => '<clave secreta aquí>',
],
```

Luego accede desde:
```
http://localhost/basic/web/
```

### Con Docker
Actualiza los paquetes:
```
docker-compose run --rm php composer update --prefer-dist
```

Instala con Composer:
```
docker-compose run --rm php composer install
```

Inicia los contenedores:
```
docker-compose up -d
```

Accede desde:
```
http://127.0.0.1:8000
```

**Notas:**
- Se requiere Docker Engine ≥ 17.04.
- El caché de Composer se almacena por defecto en `.docker-composer`.

Configuración de la Base de Datos
---------------------------------
Edita `config/db.php` con tus datos reales:
```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**Notas:**
- Yii no crea automáticamente la base de datos.
- Revisa otros archivos de `config/` para ajustes adicionales.

Pruebas
-------
Las pruebas están en el directorio `tests`, utilizando [Codeception](https://codeception.com/). Incluye suites:

- `unit` (componentes)
- `functional` (interacciones)
- `acceptance` (interfaz en navegador - desactivada por defecto)

Ejecuta todas las pruebas:
```
vendor/bin/codecept run
```

### Pruebas de Aceptación

1. Renombra `tests/acceptance.suite.yml.example` a `tests/acceptance.suite.yml`.
2. Reemplaza `codeception/base` con `codeception/codeception` en `composer.json`.
3. Ejecuta:
   ```
   composer update
   ```
4. Descarga [Selenium Server](https://www.selenium.dev/downloads/) y ejecútalo:
   ```
   java -jar selenium-server-standalone-x.xx.x.jar
   ```
   Opcional: usa Docker para Selenium + Firefox:
   ```
   docker run --net=host selenium/standalone-firefox:2.53.0
   ```
5. Crea la base `yii2basic_test` y aplica migraciones:
   ```
   tests/bin/yii migrate
   ```
6. Inicia el servidor:
   ```
   tests/bin/yii serve
   ```
7. Ejecuta pruebas:
   ```
   vendor/bin/codecept run acceptance
```

### Cobertura de Código
Habilita cobertura en `codeception.yml` y ejecuta:
```
vendor/bin/codecept run --coverage --coverage-html --coverage-xml
```

Los reportes se guardan en `tests/_output`.
