<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Este es el modelo para la tabla "roles_permisos".
 *
 * @property int $id_rol_permiso
 * @property int $id_rol
 * @property int $id_permiso
 * @property int $created_at
 *
 * @property Permiso $permiso
 * @property Rol $rol
 */
class RolPermiso extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles_permisos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rol', 'id_permiso', 'created_at'], 'required'],
            [['id_rol', 'id_permiso', 'created_at'], 'integer'],
            [['id_rol', 'id_permiso'], 'unique', 'targetAttribute' => ['id_rol', 'id_permiso']],
            [['id_permiso'], 'exist', 'skipOnError' => true, 'targetClass' => Permiso::class, 'targetAttribute' => ['id_permiso' => 'id_permiso']],
            [['id_rol'], 'exist', 'skipOnError' => true, 'targetClass' => Rol::class, 'targetAttribute' => ['id_rol' => 'id_rol']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_rol_permiso' => 'ID',
            'id_rol' => 'Rol',
            'id_permiso' => 'Permiso',
            'created_at' => 'Asignado en',
        ];
    }

    /**
     * Gets query for [[Permiso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermiso()
    {
        return $this->hasOne(Permiso::class, ['id_permiso' => 'id_permiso']);
    }

    /**
     * Gets query for [[Rol]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRol()
    {
        return $this->hasOne(Rol::class, ['id_rol' => 'id_rol']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord && empty($this->created_at)) {
                $this->created_at = time();
            }
            return true;
        }
        return false;
    }
}
