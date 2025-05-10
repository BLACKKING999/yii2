<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\components\SafeUploadedFile;
use app\components\UploadHandler;
use app\components\Validator;

/**
 * Este es el modelo normalizado para la tabla "usuarios" que combina tanto 
 * usuarios del sistema como usuarios de la biblioteca
 *
 * @property int $id_usuario
 * @property string $username
 * @property string $nombre
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property string $access_token
 * @property string $role
 * @property int $status
 * @property string|null $imagen_perfil
 * @property string|null $google_id
 * @property bool $es_google
 * @property string $fecha_registro
 * @property int $created_at
 * @property int $updated_at
 * @property string $password write-only password
 * 
 * @property Prestamo[] $prestamos
 */
class UserNormalizado extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    /**
     * Propiedad para manejo de subida de archivos
     */
    public $imagenFile;

    /**
     * Propiedad temporal para la contraseña
     */
    public $password;
    
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
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // Status y rol por defecto
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_ADMIN, self::ROLE_USER]],
            
            // Campos obligatorios y tipos
            [['username', 'nombre', 'email'], 'required'],
            [['username', 'email'], 'string', 'max' => 255],
            ['nombre', 'string', 'max' => 100],
            [['username', 'email'], 'unique'],
            ['email', 'email'],
            ['password', 'string', 'min' => 6],
            
            // Campos para Google
            ['es_google', 'boolean'],
            ['google_id', 'string', 'max' => 255],
            
            // Validación y manejo de imagen
            [['imagenFile'], 'app\components\CustomFileValidator', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 2097152],
            ['imagenFile', 'validateImage'],
            ['nombre', 'match', 'pattern' => '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', 'message' => 'El nombre solo puede contener letras y espacios.'],
            
            // Seguridad
            [['password_hash', 'auth_key', 'access_token'], 'string', 'max' => 255],
            ['fecha_registro', 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'ID',
            'username' => 'Nombre de Usuario',
            'nombre' => 'Nombre Completo',
            'email' => 'Correo Electrónico',
            'password' => 'Contraseña',
            'password_hash' => 'Contraseña',
            'role' => 'Rol',
            'status' => 'Estado',
            'imagen_perfil' => 'Imagen de Perfil',
            'imagenFile' => 'Imagen de Perfil',
            'google_id' => 'Google ID',
            'es_google' => 'Iniciar con Google',
            'fecha_registro' => 'Fecha de Registro',
            'created_at' => 'Creado en',
            'updated_at' => 'Actualizado en',
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
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id_usuario' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username or email
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id_usuario;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if ($this->es_google) {
            // Los usuarios de Google no tienen contraseña local
            return false;
        }
        
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates access token
     */
    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->generateAuthKey();
                $this->generateAccessToken();
                if (empty($this->fecha_registro)) {
                    $this->fecha_registro = date('Y-m-d H:i:s');
                }
            }
            
            if (!empty($this->password)) {
                $this->setPassword($this->password);
            }
            
            return true;
        }
        return false;
    }

    /**
     * Checks if user is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
}
