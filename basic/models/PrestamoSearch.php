<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class PrestamoSearch extends Prestamo
{
    public function rules()
    {
        return [
            [['id_prestamo', 'id_usuario', 'id_libro'], 'integer'],
            [['fecha_prestamo', 'fecha_devolucion'], 'safe'],
            [['devuelto'], 'boolean'],
        ];
    }

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

    public function search($params)
    {
        $query = Prestamo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'fecha_prestamo' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_prestamo' => $this->id_prestamo,
            'id_usuario' => $this->id_usuario,
            'id_libro' => $this->id_libro,
            'fecha_prestamo' => $this->fecha_prestamo,
            'fecha_devolucion' => $this->fecha_devolucion,
            'devuelto' => $this->devuelto,
        ]);

        return $dataProvider;
    }

    public static function getUsuariosList()
    {
        return ArrayHelper::map(Usuario::find()->all(), 'id_usuario', 'nombre');
    }

    public static function getLibrosList()
    {
        return ArrayHelper::map(Libro::find()->all(), 'id_libro', 'titulo');
    }
} 