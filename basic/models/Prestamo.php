<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "prestamos".
 *
 * @property int $id_prestamo
 * @property int $id_usuario
 * @property int $id_libro
 * @property string $fecha_prestamo
 * @property string $fecha_devolucion
 * @property bool $devuelto
 * 
 * @property Usuario $usuario
 * @property Libro $libro
 */
class Prestamo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prestamos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario', 'id_libro', 'fecha_prestamo', 'fecha_devolucion'], 'required'],
            [['id_usuario', 'id_libro'], 'integer'],
            [['fecha_prestamo', 'fecha_devolucion'], 'safe'],
            [['devuelto'], 'boolean'],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['id_usuario' => 'id_usuario']],
            [['id_libro'], 'exist', 'skipOnError' => true, 'targetClass' => Libro::class, 'targetAttribute' => ['id_libro' => 'id_libro']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_prestamo' => 'ID',
            'id_usuario' => 'Usuario',
            'id_libro' => 'Libro',
            'fecha_prestamo' => 'Fecha de Préstamo',
            'fecha_devolucion' => 'Fecha de Devolución',
            'devuelto' => 'Devuelto',
        ];
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::class, ['id_usuario' => 'id_usuario']);
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
} 