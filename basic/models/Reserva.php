<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "reservas".
 *
 * @property int $id_reserva
 * @property int $id_usuario
 * @property int $id_libro
 * @property string $fecha_reserva
 * @property bool $activa
 * @property bool $notificado
 * 
 * @property User $usuario
 * @property Libro $libro
 */
class Reserva extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reservas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario', 'id_libro', 'fecha_reserva'], 'required'],
            [['id_usuario', 'id_libro'], 'integer'],
            [['fecha_reserva'], 'safe'],
            [['activa', 'notificado'], 'boolean'],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_usuario' => 'id_usuario']],
            [['id_libro'], 'exist', 'skipOnError' => true, 'targetClass' => Libro::class, 'targetAttribute' => ['id_libro' => 'id_libro']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_reserva' => 'ID',
            'id_usuario' => 'Usuario',
            'id_libro' => 'Libro',
            'fecha_reserva' => 'Fecha de Reserva',
            'activa' => 'Activa',
            'notificado' => 'Notificado',
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
     * Gets query for [[Libro]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibro()
    {
        return $this->hasOne(Libro::class, ['id_libro' => 'id_libro']);
    }
    
    /**
     * Cancela la reserva actual
     *
     * @return bool si la operación fue exitosa
     */
    public function cancelar()
    {
        $this->activa = false;
        return $this->save();
    }
}
