<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\components\SafeUploadedFile;
use app\components\UploadHandler;
use app\components\Validator;
use app\components\CustomFileValidator;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id_usuario
 * @property string $nombre
 * @property string $correo
 * @property string|null $google_id
 * @property bool $es_google
 * @property string $contrasena
 * @property string|null $imagen_perfil
 * @property string $fecha_registro
 */
class Usuario extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'correo'], 'required'],
            [['contrasena'], 'required', 'when' => function($model) {
                return !$model->es_google;
            }, 'whenClient' => "function (attribute, value) {
                return !$('#usuario-es_google').is(':checked');
            }"],
            [['fecha_registro'], 'safe'],
            [['es_google'], 'boolean'],
            [['nombre'], 'string', 'max' => 100],
            [['correo', 'google_id'], 'string', 'max' => 100],
            [['contrasena'], 'string', 'max' => 255],
            [['imagen_perfil'], 'string', 'max' => 255],
            [['correo'], 'unique'],
            [['imagenFile'], 'app\components\CustomFileValidator', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 2097152],
            ['imagenFile', 'validateImage'],
            ['correo', 'email'],
            ['nombre', 'match', 'pattern' => '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', 'message' => 'El nombre solo puede contener letras y espacios.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'ID',
            'nombre' => 'Nombre',
            'correo' => 'Correo',
            'google_id' => 'Google ID',
            'es_google' => 'Iniciar con Google',
            'contrasena' => 'Contraseña',
            'imagen_perfil' => 'Imagen de Perfil',
            'imagenFile' => 'Imagen de Perfil',
            'fecha_registro' => 'Fecha de Registro',
        ];
    }

    /**
     * Gets query for [[Prestamos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrestamos()
    {
        return $this->hasMany(Prestamo::class, ['id_usuario' => 'id_usuario']);
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
     * Procesa la carga de imágenes
     */
    public function upload()
    {
        if ($this->validate()) {
            $this->imagenFile = SafeUploadedFile::getInstance($this, 'imagenFile');
            if ($this->imagenFile) {
                // Eliminar imagen anterior si existe
                if ($this->imagen_perfil) {
                    UploadHandler::deleteImage($this->imagen_perfil, 'usuarios');
                }
                
                // Guardar nueva imagen
                $this->imagen_perfil = UploadHandler::saveImage($this->imagenFile, 'usuarios');
                return true;
            }
            return true; // No hay imagen para subir pero el modelo es válido
        }
        return false;
    }

    /**
     * Obtiene la URL de la imagen de perfil
     */
    public function getImagenUrl()
    {
        return UploadHandler::getImageUrl($this->imagen_perfil, 'usuarios');
    }

    /**
     * Antes de guardar el modelo
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Si es un nuevo registro, establecer fecha de registro
            if ($this->isNewRecord) {
                $this->fecha_registro = date('Y-m-d H:i:s');
            }
            // Sólo hashear la contraseña si es un nuevo registro y no es Google
            if ($this->isNewRecord && !$this->es_google && !empty($this->contrasena)) {
                $this->contrasena = Yii::$app->security->generatePasswordHash($this->contrasena);
            }
            return true;
        }
        return false;
    }

    /* ------------------- Métodos requeridos por IdentityInterface ----------------- */

    /**
     * Busca un usuario por su ID
     * @param int|string $id ID del usuario a buscar
     * @return Usuario|null El objeto Usuario si se encuentra o null
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id_usuario' => $id]);
    }

    /**
     * Busca un usuario por token de acceso
     * @param string $token Token de acceso a buscar
     * @param mixed $type Tipo de token
     * @return Usuario|null El objeto Usuario si se encuentra o null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // No implementado, usar google_id para tokens externos
        return null;
    }

    /**
     * Obtiene el ID del usuario
     * @return int|string ID del usuario actual
     */
    public function getId()
    {
        return $this->id_usuario;
    }

    /**
     * Obtiene la clave de autenticación
     * @return string La clave de autenticación
     */
    public function getAuthKey()
    {
        // No implementado, usar es_google para autenticación externa
        return null;
    }

    /**
     * Valida la clave de autenticación
     * @param string $authKey Clave de autenticación a validar
     * @return bool Si la clave de autenticación es válida
     */
    public function validateAuthKey($authKey)
    {
        // No implementado, usar es_google para autenticación externa
        return false;
    }

    /**
     * Valida la contraseña del usuario
     * @param string $password Contraseña a validar
     * @return bool Si la contraseña es válida
     */
    public function validatePassword($password)
    {
        if ($this->es_google) {
            // Los usuarios de Google no tienen contraseña local
            return false;
        }

        return Yii::$app->security->validatePassword($password, $this->contrasena);
    }
}