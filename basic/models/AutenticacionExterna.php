<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Este es el modelo para la tabla "autenticacion_externa".
 *
 * @property int $id_auth_externa
 * @property int $id_usuario
 * @property string $proveedor
 * @property string $proveedor_id
 * @property string|null $token_acceso
 * @property string $ultimo_acceso
 *
 * @property User $usuario
 */
class AutenticacionExterna extends ActiveRecord
{
    /**
     * Constantes para proveedores de autenticación
     */
    const PROVEEDOR_GOOGLE = 'google';
    const PROVEEDOR_FACEBOOK = 'facebook';
    const PROVEEDOR_TWITTER = 'twitter';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'autenticacion_externa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario', 'proveedor', 'proveedor_id'], 'required'],
            [['id_usuario'], 'integer'],
            [['ultimo_acceso'], 'safe'],
            [['proveedor'], 'string', 'max' => 50],
            [['proveedor_id', 'token_acceso'], 'string', 'max' => 255],
            [['proveedor', 'proveedor_id'], 'unique', 'targetAttribute' => ['proveedor', 'proveedor_id']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_auth_externa' => 'ID',
            'id_usuario' => 'Usuario',
            'proveedor' => 'Proveedor',
            'proveedor_id' => 'ID del Proveedor',
            'token_acceso' => 'Token de Acceso',
            'ultimo_acceso' => 'Último Acceso',
        ];
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(User::class, ['id_usuario' => 'id_usuario']);
    }
    
    /**
     * Busca un usuario por su proveedor y ID de autenticación externa
     * @param string $proveedor El proveedor de autenticación (google, facebook, etc.)
     * @param string $proveedorId El ID del usuario en el sistema del proveedor
     * @return User|null El usuario encontrado o null
     */
    public static function buscarUsuarioPorAutenticacionExterna($proveedor, $proveedorId)
    {
        $auth = self::findOne(['proveedor' => $proveedor, 'proveedor_id' => $proveedorId]);
        if ($auth) {
            return $auth->usuario;
        }
        return null;
    }
    
    /**
     * Vincula un usuario con un proveedor de autenticación externa
     * @param int $idUsuario ID del usuario a vincular
     * @param string $proveedor El proveedor de autenticación (google, facebook, etc.)
     * @param string $proveedorId El ID del usuario en el sistema del proveedor
     * @param string $tokenAcceso Token de acceso al API del proveedor
     * @return AutenticacionExterna|null La instancia creada o null si falló
     */
    public static function vincularUsuario($idUsuario, $proveedor, $proveedorId, $tokenAcceso = null)
    {
        // Verificar si ya existe una vinculación
        $auth = self::findOne(['proveedor' => $proveedor, 'proveedor_id' => $proveedorId]);
        
        if ($auth) {
            // Si existe, actualizar el token y la fecha
            $auth->token_acceso = $tokenAcceso;
            $auth->ultimo_acceso = date('Y-m-d H:i:s');
            $auth->save();
            return $auth;
        }
        
        // Si no existe, crear una nueva vinculación
        $auth = new self();
        $auth->id_usuario = $idUsuario;
        $auth->proveedor = $proveedor;
        $auth->proveedor_id = $proveedorId;
        $auth->token_acceso = $tokenAcceso;
        $auth->ultimo_acceso = date('Y-m-d H:i:s');
        
        return $auth->save() ? $auth : null;
    }
    
    /**
     * Actualiza el token de acceso y la fecha del último acceso
     * @param string $tokenAcceso El nuevo token de acceso
     * @return bool Si se actualizó correctamente
     */
    public function actualizarToken($tokenAcceso)
    {
        $this->token_acceso = $tokenAcceso;
        $this->ultimo_acceso = date('Y-m-d H:i:s');
        return $this->save();
    }
}
