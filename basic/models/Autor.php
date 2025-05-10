<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\SafeUploadedFile;
use app\components\UploadHandler;
use app\components\Validator;
use app\components\CustomFileValidator;

/**
 * This is the model class for table "autores".
 *
 * @property int $id_autor
 * @property string $nombre_autor
 * @property string|null $imagen_autor
 * @property string $nacionalidad
 */
class Autor extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'autores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_autor', 'nacionalidad'], 'required'],
            [['nombre_autor'], 'string', 'max' => 100],
            [['nacionalidad'], 'string', 'max' => 50],
            [['imagen_autor'], 'string', 'max' => 255],
            [['imagenFile'], 'app\components\CustomFileValidator', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 2097152],
            ['imagenFile', 'validateImage'],
            ['nombre_autor', 'match', 'pattern' => '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.]+$/', 'message' => 'El nombre solo puede contener letras, espacios y puntos.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_autor' => 'ID',
            'nombre_autor' => 'Nombre del Autor',
            'nacionalidad' => 'Nacionalidad',
            'imagen_autor' => 'Imagen del Autor',
            'imagenFile' => 'Imagen del Autor',
        ];
    }

    /**
     * Gets query for [[Libros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibros()
    {
        return $this->hasMany(Libro::class, ['id_autor' => 'id_autor']);
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
        $file = SafeUploadedFile::getInstance($this, $attribute);
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
        if ($this->validate()) {
            $this->imagenFile = SafeUploadedFile::getInstance($this, 'imagenFile');
            if ($this->imagenFile) {
                // Eliminar imagen anterior si existe
                if ($this->imagen_autor) {
                    UploadHandler::deleteImage($this->imagen_autor, 'autores');
                }
                
                // Guardar nueva imagen
                $this->imagen_autor = UploadHandler::saveImage($this->imagenFile, 'autores');
                return true;
            }
            return true; // No hay imagen para subir pero el modelo es válido
        }
        return false;
    }

    /**
     * Obtiene la URL de la imagen del autor
     */
    public function getImagenUrl()
    {
        return UploadHandler::getImageUrl($this->imagen_autor, 'autores');
    }
    
    /**
     * Devuelve el nombre completo del autor
     * @return string
     */
    public function getNombreCompleto()
    {
        return $this->nombre_autor;
    }
}