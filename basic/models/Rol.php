<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Este es el modelo para la tabla "roles".
 *
 * @property int $id_rol
 * @property string $nombre_rol
 * @property string|null $descripcion
 * @property int $nivel_acceso
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User[] $usuarios
 * @property RolPermiso[] $rolesPermisos
 * @property Permiso[] $permisos
 */
class Rol extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles';
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
            [['nombre_rol', 'nivel_acceso'], 'required'],
            [['nivel_acceso', 'created_at', 'updated_at'], 'integer'],
            [['nombre_rol'], 'string', 'max' => 50],
            [['descripcion'], 'string', 'max' => 255],
            [['nombre_rol'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_rol' => 'ID',
            'nombre_rol' => 'Nombre del Rol',
            'descripcion' => 'Descripción',
            'nivel_acceso' => 'Nivel de Acceso',
            'created_at' => 'Creado en',
            'updated_at' => 'Actualizado en',
        ];
    }

    /**
     * Gets query for [[Usuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(User::class, ['id_rol' => 'id_rol']);
    }

    /**
     * Gets query for [[RolesPermisos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRolesPermisos()
    {
        return $this->hasMany(RolPermiso::class, ['id_rol' => 'id_rol']);
    }

    /**
     * Gets query for [[Permisos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermisos()
    {
        return $this->hasMany(Permiso::class, ['id_permiso' => 'id_permiso'])
            ->via('rolesPermisos');
    }
    
    /**
     * Asigna un permiso a este rol
     * 
     * @param int $id_permiso ID del permiso a asignar
     * @return bool Si se asignó correctamente
     */
    public function asignarPermiso($id_permiso)
    {
        // Verificar si ya tiene este permiso
        if (RolPermiso::find()->where(['id_rol' => $this->id_rol, 'id_permiso' => $id_permiso])->exists()) {
            return true; // Ya tiene el permiso, consideramos que fue exitoso
        }
        
        $rolPermiso = new RolPermiso();
        $rolPermiso->id_rol = $this->id_rol;
        $rolPermiso->id_permiso = $id_permiso;
        $rolPermiso->created_at = time();
        
        return $rolPermiso->save();
    }
    
    /**
     * Revoca un permiso de este rol
     * 
     * @param int $id_permiso ID del permiso a revocar
     * @return bool Si se revocó correctamente
     */
    public function revocarPermiso($id_permiso)
    {
        $rolPermiso = RolPermiso::findOne(['id_rol' => $this->id_rol, 'id_permiso' => $id_permiso]);
        if ($rolPermiso) {
            return $rolPermiso->delete() > 0;
        }
        return true; // Si no existe, consideramos que fue exitoso
    }
    
    /**
     * Verifica si el rol tiene un permiso específico
     * 
     * @param string $nombrePermiso Nombre del permiso a verificar
     * @return bool Si tiene el permiso
     */
    public function tienePermiso($nombrePermiso)
    {
        $permiso = Permiso::findOne(['nombre_permiso' => $nombrePermiso]);
        if (!$permiso) {
            return false;
        }
        
        return RolPermiso::find()
            ->where(['id_rol' => $this->id_rol, 'id_permiso' => $permiso->id_permiso])
            ->exists();
    }
}
