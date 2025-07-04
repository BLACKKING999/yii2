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
 * @property string|null $departamento
 * @property string|null $cargo
 * @property string|null $fecha_contratacion
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $user
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
            'id_profesor' => 'ID Profesor',
            'id_usuario' => 'ID Usuario',
            'departamento' => 'Departamento',
            'cargo' => 'Cargo',
            'fecha_contratacion' => 'Fecha de Contratación',
            'created_at' => 'Creado en',
            'updated_at' => 'Actualizado en',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id_usuario' => 'id_usuario'])->with(['rol']);
    }

    /**
     * Factory method para crear un nuevo profesor a partir de un usuario existente
     * 
     * @param User $user Usuario base
     * @return UsuarioProfesor
     */
    public static function crearDesdeUsuario(User $user)
    {
        $profesor = new self();
        $profesor->id_usuario = $user->id_usuario;
        return $profesor;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            $this->updated_at = time();
            return true;
        }
        return false;
    }

    /**
     * Obtiene el nombre completo del usuario
     * @return string
     */
    public function getNombreCompleto()
    {
        return $this->user ? $this->user->nombre . ' ' . $this->user->apellidos : '';
    }
}
