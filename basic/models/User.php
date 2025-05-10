<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model - Modelo para la tabla usuarios normalizada
 *
 * @property int $id_usuario
 * @property string $username
 * @property string $nombre
 * @property string $apellidos
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property string $access_token
 * @property int $id_rol
 * @property int $status
 * @property string $imagen_perfil
 * @property string $fecha_registro
 * @property int $created_at
 * @property int $updated_at
 * 
 * @property Rol $rol
 * @property UsuarioEstudiante $estudiante
 * @property UsuarioProfesor $profesor
 * @property UsuarioPersonal $personal
 * @property Prestamo[] $prestamos
 * @property AutenticacionExterna[] $autenticacionesExternas
 * 
 * @property string $password write-only password
 * @property string $imagenFile write-only image file
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    /**
     * @var string Propiedad temporal para la contraseña
     */
    public $password;
    
    /**
     * @var \yii\web\UploadedFile Archivo de imagen de perfil
     */
    public $imagenFile;

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
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['username', 'nombre', 'email', 'id_rol'], 'required'],
            [['username', 'email'], 'string', 'max' => 255],
            ['nombre', 'string', 'max' => 100],
            ['apellidos', 'string', 'max' => 100],
            [['username', 'email'], 'unique'],
            ['email', 'email'],
            ['password', 'string', 'min' => 6],
            ['id_rol', 'exist', 'skipOnError' => true, 'targetClass' => Rol::class, 'targetAttribute' => ['id_rol' => 'id_rol']],
            [['imagenFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 2097152],
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
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'email' => 'Correo Electrónico',
            'password' => 'Contraseña',
            'id_rol' => 'Rol',
            'status' => 'Estado',
            'imagen_perfil' => 'Imagen de Perfil',
            'imagenFile' => 'Imagen de Perfil',
            'fecha_registro' => 'Fecha de Registro',
            'created_at' => 'Creado en',
            'updated_at' => 'Actualizado en',
        ];
    }

    /**
     * Gets query for [[Rol]]
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRol()
    {
        return $this->hasOne(Rol::class, ['id_rol' => 'id_rol']);
    }
    
    /**
     * Gets query for [[UsuarioEstudiante]]
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstudiante()
    {
        return $this->hasOne(UsuarioEstudiante::class, ['id_usuario' => 'id_usuario']);
    }
    
    /**
     * Gets query for [[UsuarioProfesor]]
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfesor()
    {
        return $this->hasOne(UsuarioProfesor::class, ['id_usuario' => 'id_usuario']);
    }
    
    /**
     * Gets query for [[UsuarioPersonal]]
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersonal()
    {
        return $this->hasOne(UsuarioPersonal::class, ['id_usuario' => 'id_usuario']);
    }
    
    /**
     * Gets query for [[Prestamos]]
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrestamos()
    {
        return $this->hasMany(Prestamo::class, ['id_usuario' => 'id_usuario']);
    }
    
    /**
     * Gets query for [[AutenticacionesExternas]]
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAutenticacionesExternas()
    {
        return $this->hasMany(AutenticacionExterna::class, ['id_usuario' => 'id_usuario']);
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
     * Finds user by username
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
     * Sube y guarda la imagen de perfil
     * @return boolean
     */
    public function uploadImage()
    {
        if ($this->validate()) {
            if ($this->imagenFile) {
                // Primero eliminar la imagen anterior si existe
                if ($this->imagen_perfil && file_exists(Yii::getAlias('@webroot/uploads/usuarios/') . $this->imagen_perfil)) {
                    unlink(Yii::getAlias('@webroot/uploads/usuarios/') . $this->imagen_perfil);
                }
                
                // Crear directorio si no existe
                $uploadPath = Yii::getAlias('@webroot/uploads/usuarios/');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Generar nombre único para la imagen
                $fileName = Yii::$app->security->generateRandomString() . '.' . $this->imagenFile->extension;
                
                // Guardar archivo
                $this->imagenFile->saveAs($uploadPath . $fileName);
                $this->imagen_perfil = $fileName;
                return true;
            }
            return true; // No hay imagen para guardar pero el modelo es válido
        }
        return false;
    }
    
    /**
     * Obtiene la URL de la imagen de perfil
     * @return string
     */
    public function getImagenUrl()
    {
        if ($this->imagen_perfil) {
            return Yii::getAlias('@web/uploads/usuarios/') . $this->imagen_perfil;
        }
        return Yii::getAlias('@web/img/default-profile.png'); // Imagen por defecto
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
    /**
     * @deprecated Esta función ha sido reemplazada
     * @return bool
     */
    private function _oldIsAdmin()
    {
        return $this->rol && $this->rol->nombre_rol === 'admin';
    }
    
    /**
     * Checks if user is bibliotecario
     *
     * @return bool
     */
    /**
     * @deprecated Esta función ha sido reemplazada
     * @return bool
     */
    private function _oldIsBibliotecario()
    {
        return $this->rol && $this->rol->nombre_rol === 'bibliotecario';
    }
    
    /**
     * Checks if user is profesor
     *
     * @return bool
     */
    /**
     * @deprecated Esta función ha sido reemplazada
     * @return bool
     */
    private function _oldIsProfesor()
    {
        return $this->rol && $this->rol->nombre_rol === 'profesor';
    }
    
    /**
     * Checks if user is estudiante
     *
     * @return bool
     */
    private function _oldIsEstudiante()
    {
        return $this->rol && $this->rol->nombre_rol === 'estudiante';
    }
    
    /**
     * Checks if user has a specific permission
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission($permissionName)
    {
        if (!$this->rol) {
            return false;
        }
        
        // Buscar el permiso por nombre
        $permission = Permiso::findOne(['nombre_permiso' => $permissionName]);
        if (!$permission) {
            return false;
        }
        
        // Verificar si el rol tiene el permiso
        return RolPermiso::find()
            ->where(['id_rol' => $this->id_rol, 'id_permiso' => $permission->id_permiso])
            ->exists();
    }
    
    /**
     * Devuelve el nombre completo del usuario
     * @return string
     */
    public function getNombreCompleto()
    {
        return $this->nombre . ' ' . $this->apellidos;
    }
    
    /**
     * Devuelve el tipo específico de usuario (estudiante, profesor, personal)
     * @return string
     */
    public function getTipoUsuario()
    {
        if ($this->estudiante) {
            return 'Estudiante';
        } elseif ($this->profesor) {
            return 'Profesor';
        } elseif ($this->personal) {
            return 'Personal';
        } else {
            return 'Usuario';
        }
    }
    
    /**
     * Verifica si el usuario puede administrar préstamos
     * @return bool
     */
    public function puedeAdministrarPrestamos()
    {
        return $this->isAdmin() || $this->isBibliotecario() || $this->hasPermission('gestionar_prestamos');
    }
    
    /**
     * Verifica si el usuario puede gestionar préstamos
     * @return bool
     */
    public function puedeGestionarPrestamos()
    {
        return $this->isAdmin() || $this->isBibliotecario() || $this->hasPermission('gestionar_prestamos');
    }
    
    /**
     * Verifica si el usuario puede administrar usuarios
     * @return bool
     */
    public function puedeAdministrarUsuarios()
    {
        return $this->isAdmin() || $this->hasPermission('gestionar_usuarios');
    }
    
    /**
     * Verifica si el usuario puede administrar roles
     * @return bool
     */
    public function puedeAdministrarRoles()
    {
        return $this->isAdmin() || $this->hasPermission('administrar_roles');
    }
    
    /**
     * Verifica si el usuario puede administrar libros
     * @return bool
     */
    public function puedeAdministrarLibros()
    {
        return $this->isAdmin() || $this->isBibliotecario() || $this->hasPermission('administrar_libros');
    }
    
    /**
     * Verifica si el usuario es estudiante
     * @return bool
     */
    public function isEstudiante()
    {
        return $this->hasOne(UsuarioEstudiante::class, ['id_usuario' => 'id_usuario'])->exists();
    }
    
    /**
     * Verifica si el usuario es estudiante (compatibilidad con vistas)
     * @return bool
     */
    public function esEstudiante()
    {
        return $this->isEstudiante();
    }
    
    /**
     * Verifica si el usuario es profesor
     * @return bool
     */
    public function isProfesor()
    {
        return $this->hasOne(UsuarioProfesor::class, ['id_usuario' => 'id_usuario'])->exists();
    }
    
    /**
     * Verifica si el usuario es profesor (compatibilidad con vistas)
     * @return bool
     */
    public function esProfesor()
    {
        return $this->isProfesor();
    }
    
    /**
     * Verifica si el usuario es administrador
     * @return bool
     */
    public function isAdmin()
    {
        return $this->rol && $this->rol->nombre_rol === 'Administrador';
    }
    
    /**
     * Verifica si el usuario es administrador (compatibilidad con vistas)
     * @return bool
     */
    public function esAdmin()
    {
        return $this->isAdmin();
    }
    
    /**
     * Verifica si el usuario es bibliotecario
     * @return bool
     */
    public function isBibliotecario()
    {
        return $this->rol && $this->rol->nombre_rol === 'Bibliotecario';
    }
    
    /**
     * Verifica si el usuario es bibliotecario (compatibilidad con vistas)
     * @return bool
     */
    public function esBibliotecario()
    {
        return $this->isBibliotecario();
    }
    
    /**
     * Obtiene la relación con el modelo UsuarioEstudiante
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioEstudiante()
    {
        return $this->hasOne(UsuarioEstudiante::class, ['id_usuario' => 'id_usuario']);
    }
    
    /**
     * Obtiene la relación con el modelo UsuarioProfesor
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioProfesor()
    {
        return $this->hasOne(UsuarioProfesor::class, ['id_usuario' => 'id_usuario']);
    }
}
