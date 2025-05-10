<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Este es el modelo para la tabla "usuarios_personal".
 *
 * @property int $id_personal
 * @property int $id_usuario
 * @property string|null $departamento
 * @property string|null $cargo
 * @property string|null $fecha_contratacion
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $usuario
 */
class UsuarioPersonal extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios_personal';
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
            [['id_usuario'], 'required'],
            [['id_usuario', 'created_at', 'updated_at'], 'integer'],
            [['fecha_contratacion'], 'safe'],
            [['departamento', 'cargo'], 'string', 'max' => 100],
            [['id_usuario'], 'unique'],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_personal' => 'ID',
            'id_usuario' => 'Usuario',
            'departamento' => 'Departamento',
            'cargo' => 'Cargo',
            'fecha_contratacion' => 'Fecha de Contratación',
            'created_at' => 'Creado en',
            'updated_at' => 'Actualizado en',
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
     * Factory method para crear un nuevo personal a partir de un usuario existente
     * 
     * @param User $user Usuario base
     * @param array $attributes Atributos específicos de personal
     * @return UsuarioPersonal|null El objeto creado o null si falló
     */
    public static function crearDesdeUsuario($user, $attributes = [])
    {
        if (!$user || !$user->id_usuario) {
            return null;
        }
        
        $personal = new self();
        $personal->id_usuario = $user->id_usuario;
        
        // Asignar atributos específicos
        if (isset($attributes['departamento'])) {
            $personal->departamento = $attributes['departamento'];
        }
        if (isset($attributes['cargo'])) {
            $personal->cargo = $attributes['cargo'];
        }
        if (isset($attributes['fecha_contratacion'])) {
            $personal->fecha_contratacion = $attributes['fecha_contratacion'];
        }
        
        return $personal->save() ? $personal : null;
    }
}
