<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Este es el modelo para la tabla "usuarios_profesores".
 *
 * @property int $id_profesor
 * @property int $id_usuario
 * @property string|null $especialidad
 * @property string|null $departamento
 * @property string|null $oficina
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $usuario
 */
class UsuarioProfesor extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios_profesores';
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
            [['especialidad', 'departamento'], 'string', 'max' => 100],
            [['oficina'], 'string', 'max' => 50],
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
            'id_profesor' => 'ID',
            'id_usuario' => 'Usuario',
            'especialidad' => 'Especialidad',
            'departamento' => 'Departamento',
            'oficina' => 'Oficina',
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
     * Factory method para crear un nuevo profesor a partir de un usuario existente
     * 
     * @param User $user Usuario base
     * @param array $attributes Atributos específicos de profesor
     * @return UsuarioProfesor|null El objeto creado o null si falló
     */
    public static function crearDesdeUsuario($user, $attributes = [])
    {
        if (!$user || !$user->id_usuario) {
            return null;
        }
        
        $profesor = new self();
        $profesor->id_usuario = $user->id_usuario;
        
        // Asignar atributos específicos
        if (isset($attributes['especialidad'])) {
            $profesor->especialidad = $attributes['especialidad'];
        }
        if (isset($attributes['departamento'])) {
            $profesor->departamento = $attributes['departamento'];
        }
        if (isset($attributes['oficina'])) {
            $profesor->oficina = $attributes['oficina'];
        }
        
        return $profesor->save() ? $profesor : null;
    }
}
