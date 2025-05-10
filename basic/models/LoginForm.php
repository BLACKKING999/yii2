<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Usuario o Correo Electrónico',
            'password' => 'Contraseña',
            'rememberMe' => 'Recordarme',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Usuario o contraseña incorrectos.');
            } else if ($user->status != User::STATUS_ACTIVE) {
                $this->addError($attribute, 'Tu cuenta está inactiva o suspendida.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $resultado = Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
            
            if ($resultado) {
                // Actualizar último acceso si tenemos autenticación externa
                if ($user && $externalAuth = AutenticacionExterna::findOne(['id_usuario' => $user->id_usuario])) {
                    $externalAuth->ultimo_acceso = date('Y-m-d H:i:s');
                    $externalAuth->save(false);
                }
            }
            
            return $resultado;
        }
        return false;
    }

    /**
     * Logs in a user using external authentication provider.
     * @param string $provider Provider name (google, facebook, etc.)
     * @param string $providerId Provider user ID
     * @param array $userAttributes User attributes from the provider
     * @param string $accessToken Access token from the provider
     * @return bool|User whether the user is logged in successfully or the new user object
     */
    public function loginExternal($provider, $providerId, $userAttributes, $accessToken = null)
    {
        // Buscar usuario por autenticación externa
        $user = AutenticacionExterna::buscarUsuarioPorAutenticacionExterna($provider, $providerId);
        
        // Si no existe, buscar por email
        if (!$user && isset($userAttributes['email'])) {
            $user = User::findByEmail($userAttributes['email']);
            
            // Si encontramos un usuario con ese email, vincular con el proveedor externo
            if ($user) {
                AutenticacionExterna::vincularUsuario($user->id_usuario, $provider, $providerId, $accessToken);
            } else {
                // Crear un nuevo usuario con los datos del proveedor
                $nombreUsuario = strtolower(preg_replace('/[^a-zA-Z0-9]/', '.', $userAttributes['name']));
                $nombreUsuario = $this->generarUsernameUnico($nombreUsuario);
                
                $user = new User();
                $user->username = $nombreUsuario;
                $user->nombre = $userAttributes['name'];
                $user->email = $userAttributes['email'];
                $user->id_rol = $this->obtenerRolPorDefecto(); // Rol de estudiante o usuario básico
                $user->status = User::STATUS_ACTIVE;
                $user->password = Yii::$app->security->generateRandomString(12); // Contraseña aleatoria
                
                if ($user->save()) {
                    // Vincular con el proveedor externo
                    AutenticacionExterna::vincularUsuario($user->id_usuario, $provider, $providerId, $accessToken);
                    return $user;
                }
                
                return false;
            }
        }
        
        if ($user) {
            return Yii::$app->user->login($user, 3600*24*30);
        }
        
        return false;
    }

    /**
     * Finds user by [[username]] or email
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            // Intentar buscar por nombre de usuario
            $this->_user = User::findByUsername($this->username);
            
            // Si no se encuentra, intentar buscar por email
            if ($this->_user === null) {
                $this->_user = User::findByEmail($this->username);
            }
        }

        return $this->_user;
    }
    
    /**
     * Genera un nombre de usuario único si ya existe en la base de datos
     * @param string $baseUsername Nombre de usuario base
     * @return string Nombre de usuario único
     */
    private function generarUsernameUnico($baseUsername)
    {
        $username = $baseUsername;
        $counter = 1;
        
        while (User::findByUsername($username)) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
    
    /**
     * Obtiene el ID del rol por defecto para nuevos usuarios
     * @return int ID del rol
     */
    private function obtenerRolPorDefecto()
    {
        // Buscar rol de estudiante (normalmente id_rol = 4)
        $rol = Rol::findOne(['nombre_rol' => 'estudiante']);
        if ($rol) {
            return $rol->id_rol;
        }
        
        // Si no lo encuentra, buscar el rol con menor nivel de acceso
        $rol = Rol::find()->orderBy(['nivel_acceso' => SORT_ASC])->one();
        if ($rol) {
            return $rol->id_rol;
        }
        
        // Si no hay roles definidos, devolver 4 (valor por defecto para 'estudiante')
        return 4;
    }
}
