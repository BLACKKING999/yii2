<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

/**
 * Modelo de usuario
 *
 * @property integer $id_usuario
 * @property string $username
 * @property string $password_hash
 * @property string $nombre
 * @property string $apellidos
 * @property string $email
 * @property string $auth_key
 * @property string $access_token
 * @property integer $id_rol
 * @property integer $status
 * @property string $imagen_perfil
 * @property string $fecha_registro
 * @property integer $created_at
 * @property integer $updated_at
 * @property UploadedFile $imagenFile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_SELF_UPDATE = 'self-update';

    /**
     * @var string Campo temporal para la contraseña
     */
    public $password;

    /**
     * @var UploadedFile
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
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'nombre', 'apellidos', 'email', 'id_rol'], 'required'],
            [['id_rol', 'status'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['username', 'nombre', 'apellidos', 'email'], 'string', 'max' => 255],
            [['imagen_perfil'], 'string', 'max' => 100],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['imagenFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2], // 2MB
            [['password'], 'string', 'min' => 6],
            [['password'], 'required', 'on' => 'create'],
            
            // Reglas específicas para auto-actualización
            [['id_rol', 'status'], 'safe', 'on' => 'self-update'],
            [['username', 'email'], 'unique', 'on' => 'self-update'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['username', 'nombre', 'apellidos', 'email', 'password', 'id_rol', 'imagenFile'];
        $scenarios[self::SCENARIO_UPDATE] = ['username', 'nombre', 'apellidos', 'email', 'password', 'id_rol', 'status', 'imagenFile'];
        $scenarios[self::SCENARIO_SELF_UPDATE] = ['nombre', 'apellidos', 'email', 'password', 'imagenFile'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'ID',
            'username' => 'Usuario',
            'password' => 'Contraseña',
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'email' => 'Correo Electrónico',
            'id_rol' => 'Rol',
            'status' => 'Estado',
            'fecha_registro' => 'Fecha de Registro',
            'imagen_perfil' => 'Imagen de Perfil',
            'created_at' => 'Creado',
            'updated_at' => 'Actualizado',
            'imagenFile' => 'Imagen de Perfil',
        ];
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
     * Encuentra usuario por username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Encuentra usuario por email
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
        return $this->getPrimaryKey();
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
     * Valida la contraseña
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Genera el hash de la contraseña
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Genera una nueva clave de autenticación
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->generateAuthKey();
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
     * Obtiene la relación con el rol
     */
    public function getRol()
    {
        return $this->hasOne(Rol::class, ['id_rol' => 'id_rol']);
    }

    /**
     * Obtiene la relación con el estudiante
     */
    public function getUsuarioEstudiante()
    {
        return $this->hasOne(UsuarioEstudiante::class, ['id_usuario' => 'id_usuario']);
    }

    /**
     * Obtiene la relación con el profesor
     */
    public function getUsuarioProfesor()
    {
        return $this->hasOne(UsuarioProfesor::class, ['id_usuario' => 'id_usuario']);
    }

    /**
     * Obtiene la relación con el personal
     */
    public function getUsuarioPersonal()
    {
        return $this->hasOne(UsuarioPersonal::class, ['id_usuario' => 'id_usuario']);
    }

    /**
     * Verifica si el usuario es estudiante
     */
    public function esEstudiante()
    {
        return $this->getUsuarioEstudiante()->exists();
    }

    /**
     * Verifica si el usuario es profesor
     */
    public function esProfesor()
    {
        return $this->getUsuarioProfesor()->exists();
    }

    /**
     * Verifica si el usuario es personal
     */
    public function esPersonal()
    {
        return $this->getUsuarioPersonal()->exists();
    }

    /**
     * Obtiene el tipo de usuario
     */
    public function getTipoUsuario()
    {
        if ($this->esEstudiante()) {
            return 'estudiante';
        } elseif ($this->esProfesor()) {
            return 'profesor';
        } elseif ($this->esPersonal()) {
            return 'personal';
        }
        return 'usuario';
    }

    /**
     * Obtiene el nombre completo del usuario
     */
    public function getNombreCompleto()
    {
        return $this->nombre . ' ' . $this->apellidos;
    }

    /**
     * Verifica si el usuario puede administrar usuarios
     */
    public function puedeAdministrarUsuarios()
    {
        return $this->id_rol === 1; // 1 = Administrador
    }

    /**
     * Verifica si el usuario puede administrar roles
     */
    public function puedeAdministrarRoles()
    {
        return $this->id_rol === 1; // 1 = Administrador
    }

    /**
     * Verifica si el usuario puede administrar libros
     */
    public function puedeAdministrarLibros()
    {
        return $this->id_rol === 1; // 1 = Administrador
    }

    /**
     * Verifica si el usuario puede administrar préstamos
     */
    public function puedeAdministrarPrestamos()
    {
        return $this->id_rol === 1; // 1 = Administrador
    }

    /**
     * Verifica si el usuario puede administrar categorías
     * @return bool
     */
    public function puedeAdministrarCategorias()
    {
        return in_array($this->id_rol, [1, 2]); // Asumiendo que 1 es admin y 2 es bibliotecario
    }

    /**
     * Verifica si el usuario puede administrar autores
     * @return bool
     */
    public function puedeAdministrarAutores()
    {
        return in_array($this->id_rol, [1, 2]); // Asumiendo que 1 es admin y 2 es bibliotecario
    }

    /**
     * Verifica si el usuario puede ver reportes
     * @return bool
     */
    public function puedeVerReportes()
    {
        return in_array($this->id_rol, [1, 2]); // Asumiendo que 1 es admin y 2 es bibliotecario
    }

    /**
     * Procesa la subida de imagen
     * @return bool
     */
    public function upload()
    {
        if ($this->imagenFile) {
            $this->imagenFile = UploadedFile::getInstance($this, 'imagenFile');
            if ($this->imagenFile) {
                $fileName = Yii::$app->security->generateRandomString() . '.' . $this->imagenFile->extension;
                $uploadPath = Yii::getAlias('@webroot/uploads/usuarios/');
                
                // Crear directorio si no existe
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                if ($this->imagenFile->saveAs($uploadPath . $fileName)) {
                    // Eliminar imagen anterior si existe
                    if ($this->imagen_perfil && file_exists($uploadPath . $this->imagen_perfil)) {
                        unlink($uploadPath . $this->imagen_perfil);
                    }
                    $this->imagen_perfil = $fileName;
                    return true;
                }
            }
        }
        return true;
    }

    /**
     * Obtiene la URL de la imagen de perfil del usuario
     * @return string URL de la imagen de perfil
     */
    public function getImagenUrl()
    {
        if ($this->imagen_perfil) {
            return Yii::getAlias('@web/uploads/usuarios/') . $this->imagen_perfil;
        }
        return Yii::getAlias('@web/images/default-avatar.png');
    }
}