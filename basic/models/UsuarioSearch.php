<?php

namespace app\models;

use yii\data\ActiveDataProvider;

class UsuarioSearch extends Usuario
{
    public function rules()
    {
        return [
            [['id_usuario'], 'integer'],
            [['nombre', 'correo', 'fecha_registro'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_usuario' => 'ID',
            'nombre' => 'Nombre',
            'correo' => 'Correo Electrónico',
            'fecha_registro' => 'Fecha de Registro',
        ];
    }

    public function search($params)
    {
        $query = Usuario::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'nombre' => SORT_ASC,
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
            'id_usuario' => $this->id_usuario,
            'fecha_registro' => $this->fecha_registro,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
              ->andFilterWhere(['like', 'correo', $this->correo]);

        return $dataProvider;
    }
} 