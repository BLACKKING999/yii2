<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class UsuarioBiblioteca extends ActiveRecord
{
    public static function tableName()
    {
        return 'usuario_biblioteca';
    }

    public function rules()
    {
        return [
            [['codigo_biblioteca', 'tipo_usuario'], 'required'],
            [['codigo_biblioteca'], 'unique'],
            [['tipo_usuario'], 'in', 'range' => ['estudiante', 'profesor', 'personal']],
        ];
    }
}