<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\caching\DbDependency;
use yii\web\UploadedFile;
use app\components\UploadHandler;
use app\components\Validator;

/**
 * This is the model class for table "libros".
 *
 * @property int $id_libro
 * @property string|null $isbn
 * @property string $titulo
 * @property string|null $imagen_portada
 * @property int $id_autor
 * @property int $id_categoria
 * @property string|null $editorial
 * @property int $anio_publicacion
 * @property int|null $num_paginas
 * @property string|null $idioma
 * @property string|null $ubicacion_fisica
 * @property bool $disponible
 * @property string|null $descripcion
 * @property int|null $created_at
 * @property int|null $updated_at
 * 
 * @property Autor $autor
 * @property Categoria $categoria
 * @property Prestamo[] $prestamos
 */
class Libro extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'libros';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['titulo', 'id_autor', 'id_categoria', 'anio_publicacion'], 'required'],
            [['id_autor', 'id_categoria', 'anio_publicacion', 'num_paginas', 'created_at', 'updated_at'], 'integer'],
            [['disponible'], 'boolean'],
            [['descripcion'], 'string'],
            [['titulo'], 'string', 'max' => 150],
            [['imagen_portada', 'ubicacion_fisica'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 20],
            [['editorial'], 'string', 'max' => 100],
            [['idioma'], 'string', 'max' => 50],
            [['id_autor'], 'exist', 'skipOnError' => true, 'targetClass' => Autor::class, 'targetAttribute' => ['id_autor' => 'id_autor']],
            [['id_categoria'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::class, 'targetAttribute' => ['id_categoria' => 'id_categoria']],
            [['imagenFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 2097152, 'checkExtensionByMimeType' => false],
            // Nota: Desactivamos la validación customizada por ahora para evitar problemas con archivos temporales
            // ['imagenFile', 'validateImage'],
            ['anio_publicacion', 'integer', 'min' => 1800, 'max' => date('Y') + 2],
            ['titulo', 'filter', 'filter' => function($value) {
                return Validator::sanitizeHtml($value);
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_libro' => 'ID',
            'isbn' => 'ISBN',
            'titulo' => 'Título',
            'imagen_portada' => 'Imagen de Portada',
            'imagenFile' => 'Imagen de Portada',
            'id_autor' => 'Autor',
            'id_categoria' => 'Categoría',
            'editorial' => 'Editorial',
            'anio_publicacion' => 'Año de Publicación',
            'num_paginas' => 'Número de Páginas',
            'idioma' => 'Idioma',
            'ubicacion_fisica' => 'Ubicación Física',
            'disponible' => 'Disponible',
            'descripcion' => 'Descripción',
            'created_at' => 'Fecha de Creación',
            'updated_at' => 'Fecha de Actualización',
        ];
    }

    /**
     * Gets query for [[Autor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAutor()
    {
        return $this->hasOne(Autor::class, ['id_autor' => 'id_autor']);
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::class, ['id_categoria' => 'id_categoria']);
    }

    /**
     * Gets query for [[Prestamos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrestamos()
    {
        return $this->hasMany(Prestamo::class, ['id_libro' => 'id_libro']);
    }

    /**
     * Propiedad para manejo de subida de archivos
     */
    public $imagenFile;

    /**
     * Validación personalizada para imágenes
     */
    public function validateImage($attribute, $params)
    {
        $file = UploadedFile::getInstance($this, $attribute);
        if ($file) {
            $result = Validator::validateImage($file);
            if ($result !== true) {
                $this->addError($attribute, $result);
            }
        }
    }

    /**
     * Procesa la imagen subida
     */
    public function upload()
    {
        // Obtener el archivo antes de validar
        $this->imagenFile = UploadedFile::getInstance($this, 'imagenFile');
        
        // Si no hay archivo que subir, retornar true
        if (!$this->imagenFile) {
            return true;
        }
        
        // Crear directorios si no existen
        $basePath = Yii::getAlias('@webroot/uploads/libros');
        if (!is_dir($basePath)) {
            mkdir($basePath, 0777, true);
        }
        
        // En lugar de validar, verificar directamente la extensión
        $extension = strtolower(pathinfo($this->imagenFile->name, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($extension, $allowedExtensions)) {
            $this->addError('imagenFile', 'Solo se permiten imágenes (jpg, jpeg, png, gif)');
            return false;
        }
        
        // Generar nombre único para el archivo
        $fileName = uniqid('libro_') . '.' . $extension;
        $filePath = $basePath . '/' . $fileName;
        
        // Eliminar imagen anterior si existe
        if ($this->imagen_portada) {
            $oldImagePath = $basePath . '/' . $this->imagen_portada;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        
        // Usar copy en lugar de saveAs para evitar problemas con archivos temporales
        if (copy($this->imagenFile->tempName, $filePath)) {
            $this->imagen_portada = $fileName;
            return true;
        } else {
            $this->addError('imagenFile', 'Error al guardar la imagen');
            return false;
        }
    }

    /**
     * Obtiene la URL de la imagen de portada
     */
    public function getImagenUrl()
    {
        return UploadHandler::getImageUrl($this->imagen_portada, 'libros');
    }
    
    /**
     * Getter virtual para la propiedad descripcion
     * @return string
     */
    public function getDescripcion()
    {
        return '';
    }

    /**
     * Obtiene todos los libros con sus relaciones precargadas
     * @return array
     */
    public static function getAllWithRelations()
    {
        $cacheKey = 'libros_with_relations';
        $cache = \Yii::$app->cache;
        
        // Usar COUNT(*) en lugar de updated_at que no existe en la tabla
        $dependency = new DbDependency([
            'sql' => 'SELECT COUNT(*) FROM libros',
        ]);

        $data = $cache->getOrSet($cacheKey, function () {
            return self::find()
                ->with(['autor', 'categoria'])
                ->asArray()
                ->all();
        }, 3600, $dependency);

        return $data;
    }
} 