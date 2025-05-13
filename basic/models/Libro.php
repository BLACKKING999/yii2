<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\caching\DbDependency;
use yii\web\UploadedFile;
use app\components\UploadHandler;
use app\components\Validator;

/**
 * Modelo de libro
 *
 * @property integer $id_libro
 * @property string $isbn
 * @property string $titulo
 * @property string $imagen_portada
 * @property integer $id_autor
 * @property integer $id_categoria
 * @property string $editorial
 * @property integer $anio_publicacion
 * @property integer $num_paginas
 * @property string $idioma
 * @property string $ubicacion_fisica
 * @property string $created_at
 * @property string $updated_at
 * @property boolean $disponible
 * @property string $descripcion
 * @property UploadedFile $imagenFile
 * 
 * @property Autor $autor
 * @property Categoria $categoria
 * @property Prestamo[] $prestamos
 */
class Libro extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $imagenFile;

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
            [['isbn', 'titulo', 'id_autor', 'id_categoria', 'editorial', 'anio_publicacion', 'num_paginas', 'idioma', 'ubicacion_fisica'], 'required'],
            [['id_autor', 'id_categoria', 'anio_publicacion', 'num_paginas'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['disponible'], 'boolean'],
            [['descripcion'], 'string'],
            [['isbn'], 'string', 'max' => 13],
            [['titulo', 'editorial', 'ubicacion_fisica'], 'string', 'max' => 255],
            [['idioma'], 'string', 'max' => 50],
            [['imagen_portada'], 'string', 'max' => 100],
            [['isbn'], 'unique'],
            [['imagenFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 2 * 1024 * 1024, 'checkExtensionByMimeType' => false],
            [['id_autor'], 'exist', 'skipOnError' => true, 'targetClass' => Autor::class, 'targetAttribute' => ['id_autor' => 'id_autor']],
            [['id_categoria'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::class, 'targetAttribute' => ['id_categoria' => 'id_categoria']],
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
            'id_autor' => 'Autor',
            'id_categoria' => 'Categoría',
            'editorial' => 'Editorial',
            'anio_publicacion' => 'Año de Publicación',
            'num_paginas' => 'Número de Páginas',
            'idioma' => 'Idioma',
            'ubicacion_fisica' => 'Ubicación Física',
            'created_at' => 'Fecha de Creación',
            'updated_at' => 'Fecha de Actualización',
            'disponible' => 'Disponible',
            'descripcion' => 'Descripción',
            'imagenFile' => 'Imagen de Portada',
        ];
    }

    /**
     * Obtiene la relación con el autor
     * @return \yii\db\ActiveQuery
     */
    public function getAutor()
    {
        return $this->hasOne(Autor::class, ['id_autor' => 'id_autor']);
    }

    /**
     * Obtiene la relación con la categoría
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::class, ['id_categoria' => 'id_categoria']);
    }

    /**
     * Gets query for [[Prestamos]].
     * @return \yii\db\ActiveQuery
     */
    public function getPrestamos()
    {
        return $this->hasMany(Prestamo::class, ['id_libro' => 'id_libro']);
    }

    /**
     * Procesa la subida de imagen
     * @return bool
     */
    public function upload()
    {
        if ($this->imagenFile === null) {
            return true;
        }

        $uploadPath = Yii::getAlias('@webroot/uploads/libros/');
        
        // Crear directorio si no existe
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Validar extensión
        $extension = strtolower($this->imagenFile->extension);
        if (!in_array($extension, ['png', 'jpg', 'jpeg'])) {
            $this->addError('imagenFile', 'Solo se permiten archivos PNG, JPG o JPEG.');
            return false;
        }

        // Validar tamaño
        if ($this->imagenFile->size > 2 * 1024 * 1024) {
            $this->addError('imagenFile', 'El archivo no debe superar los 2MB.');
            return false;
        }

        $fileName = Yii::$app->security->generateRandomString() . '.' . $extension;
        $filePath = $uploadPath . $fileName;

        if ($this->imagenFile->saveAs($filePath)) {
            // Eliminar imagen anterior si existe
            if ($this->imagen_portada && file_exists($uploadPath . $this->imagen_portada)) {
                unlink($uploadPath . $this->imagen_portada);
            }
            $this->imagen_portada = $fileName;
            return true;
        }

        $this->addError('imagenFile', 'Error al guardar la imagen.');
        return false;
    }

    /**
     * Obtiene la URL de la imagen de portada
     * @return string
     */
    public function getImagenUrl()
    {
        if ($this->imagen_portada) {
            return Yii::getAlias('@web/uploads/libros/') . $this->imagen_portada;
        }
        return Yii::getAlias('@web/images/no-image.png');
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = date('Y-m-d H:i:s');
                $this->disponible = true;
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
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