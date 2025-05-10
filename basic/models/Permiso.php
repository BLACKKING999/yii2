<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Este es el modelo para la tabla "permisos".
 *
 * @property int $id_permiso
 * @property string $nombre_permiso
 * @property string|null $descripcion
 * @property int $created_at
 * @property int $updated_at
 *
 * @property RolPermiso[] $rolesPermisos
 * @property Rol[] $roles
 */
class Permiso extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permisos';
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
            [['nombre_permiso'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['nombre_permiso'], 'string', 'max' => 100],
            [['descripcion'], 'string', 'max' => 255],
            [['nombre_permiso'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_permiso' => 'ID',
            'nombre_permiso' => 'Nombre del Permiso',
            'descripcion' => 'Descripción',
            'created_at' => 'Creado en',
            'updated_at' => 'Actualizado en',
        ];
    }

    /**
     * Gets query for [[RolesPermisos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRolesPermisos()
    {
        return $this->hasMany(RolPermiso::class, ['id_permiso' => 'id_permiso']);
    }

    /**
     * Gets query for [[Roles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Rol::class, ['id_rol' => 'id_rol'])
            ->via('rolesPermisos');
    }
    
    /**
     * Asigna este permiso a un rol
     * 
     * @param int $id_rol ID del rol al que se asignará el permiso
     * @return bool Si se asignó correctamente
     */
    public function asignarARol($id_rol)
    {
        // Verificar si ya está asignado a este rol
        if (RolPermiso::find()->where(['id_rol' => $id_rol, 'id_permiso' => $this->id_permiso])->exists()) {
            return true; // Ya está asignado, consideramos que fue exitoso
        }
        
        $rolPermiso = new RolPermiso();
        $rolPermiso->id_rol = $id_rol;
        $rolPermiso->id_permiso = $this->id_permiso;
        $rolPermiso->created_at = time();
        
        return $rolPermiso->save();
    }
}
