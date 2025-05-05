<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "categorias".
 *
 * @property int $id_categoria
 * @property string $nombre_categoria
 */
class Categoria extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categorias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_categoria'], 'required'],
            [['nombre_categoria'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_categoria' => 'ID',
            'nombre_categoria' => 'Nombre de la Categoría',
        ];
    }

    /**
     * Gets query for [[Libros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibros()
    {
        return $this->hasMany(Libro::class, ['id_categoria' => 'id_categoria']);
    }
} 