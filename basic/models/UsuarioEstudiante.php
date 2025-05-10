<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Este es el modelo para la tabla "usuarios_estudiantes".
 *
 * @property int $id_estudiante
 * @property int $id_usuario
 * @property string|null $carnet
 * @property string|null $carrera
 * @property int|null $semestre
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $usuario
 */
class UsuarioEstudiante extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios_estudiantes';
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
            [['id_usuario', 'semestre', 'created_at', 'updated_at'], 'integer'],
            [['carnet'], 'string', 'max' => 20],
            [['carrera'], 'string', 'max' => 100],
            [['carnet'], 'unique'],
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
            'id_estudiante' => 'ID',
            'id_usuario' => 'Usuario',
            'carnet' => 'Carnet',
            'carrera' => 'Carrera',
            'semestre' => 'Semestre',
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
     * Factory method para crear un nuevo estudiante a partir de un usuario existente
     * 
     * @param User $user Usuario base
     * @param array $attributes Atributos específicos de estudiante
     * @return UsuarioEstudiante|null El objeto creado o null si falló
     */
    public static function crearDesdeUsuario($user, $attributes = [])
    {
        if (!$user || !$user->id_usuario) {
            return null;
        }
        
        $estudiante = new self();
        $estudiante->id_usuario = $user->id_usuario;
        
        // Asignar atributos específicos
        if (isset($attributes['carnet'])) {
            $estudiante->carnet = $attributes['carnet'];
        }
        if (isset($attributes['carrera'])) {
            $estudiante->carrera = $attributes['carrera'];
        }
        if (isset($attributes['semestre'])) {
            $estudiante->semestre = $attributes['semestre'];
        }
        
        return $estudiante->save() ? $estudiante : null;
    }
}
