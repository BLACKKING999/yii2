# Biblioteca Virtual - Instrucciones de Instalación y Uso

## Actualizaciones implementadas

Se han realizado las siguientes mejoras al sistema:

1. **Soporte de carga de imágenes**:
   - Usuarios: imágenes de perfil
   - Autores: imágenes de autor
   - Libros: imágenes de portada

2. **Autenticación con Google**:
   - Integración con Firebase para permitir inicio de sesión con cuentas de Google
   - Gestión de usuarios identificados mediante Google

3. **Barra de navegación mejorada**:
   - Diseño responsive
   - Botones de inicio/cierre de sesión

4. **Validaciones mejoradas**:
   - Validación de tipos y tamaños de imágenes
   - Filtrado de entradas para evitar ataques XSS
   - Mensajes de error personalizados

## Pasos de configuración

### 1. Actualizar la base de datos

Ejecute el script SQL `biblioteca_update.sql` para actualizar la estructura de su base de datos:

```sql
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
```

### 2. Instalar dependencias

Ejecute el siguiente comando para instalar las nuevas dependencias:

```bash
composer update
```

### 3. Configurar variables de entorno

Cree un archivo `.env` en la raíz del proyecto basándose en el archivo `.env-example` proporcionado. Este archivo contiene la configuración para Firebase y Google OAuth.

Para la autenticación con Google, necesitará:
- Crear un proyecto en la [Consola de Google Cloud](https://console.cloud.google.com/)
- Configurar las credenciales OAuth 2.0
- Agregar las URLs de redireccionamiento autorizadas
- Copiar el Client ID y Client Secret en el archivo .env

## Estructura de directorios

```
basic/
  ├── components/              # Componentes personalizados
  │   ├── Env.php              # Gestión de variables de entorno
  │   ├── FirebaseService.php  # Servicio de autenticación con Firebase
  │   ├── UploadHandler.php    # Gestión de carga de archivos
  │   └── Validator.php        # Validación de entradas
  ├── web/
  │   └── uploads/             # Directorio para archivos subidos
  │       ├── autores/         # Imágenes de autores
  │       ├── libros/          # Imágenes de portadas de libros
  │       └── usuarios/        # Imágenes de perfil de usuarios
  └── ...
```

## Funcionalidades implementadas

### Carga de imágenes

- Al crear o editar un usuario, autor o libro, ahora puede subir una imagen asociada.
- Las imágenes se almacenan en directorios separados según su tipo.
- Se realiza validación de tipo y tamaño de archivo para garantizar la seguridad.

### Autenticación con Google

1. En la página de inicio de sesión, haga clic en "Iniciar sesión con Google"
2. Será redirigido a la página de autenticación de Google
3. Después de autenticarse, será redirigido de vuelta a la aplicación
4. Si es la primera vez que inicia sesión, se creará automáticamente una cuenta
5. Si ya tiene una cuenta con el mismo correo, se vinculará a su cuenta de Google

### Validación de entradas

Se han implementado las siguientes validaciones:
- Validación de correo electrónico
- Validación de nombres (solo letras y espacios)
- Validación de imágenes (tipos y tamaños permitidos)
- Sanitización de HTML para prevenir ataques XSS

## Consejos de mantenimiento

- Las imágenes se eliminan automáticamente al eliminar el registro asociado
- Para cambiar los límites de tamaño de archivo, edite el componente `Validator.php`
- Todas las cargas de archivos incluyen validación de tipo MIME para seguridad adicional

## Problemas conocidos y soluciones

- **Problema**: No se muestran las imágenes subidas
  - **Solución**: Verifique los permisos de la carpeta `web/uploads`

- **Problema**: Error en la autenticación con Google
  - **Solución**: Verifique que las credenciales en el archivo `.env` sean correctas y que las URLs de redirección estén configuradas correctamente en la consola de Google Cloud

- **Problema**: Error al instalar las dependencias
  - **Solución**: Asegúrese de tener instalado PHP 7.4 o superior y las extensiones requeridas
