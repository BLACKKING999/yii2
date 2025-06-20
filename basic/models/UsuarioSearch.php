<?php

namespace app\models;

use yii\data\ActiveDataProvider;

class UsuarioSearch extends User
{
    public function rules()
    {
        return [
            [['id_usuario'], 'integer'],
            [['nombre', 'email', 'fecha_registro'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_usuario' => 'ID',
            'nombre' => 'Nombre',
            'email' => 'Correo Electrónico',
            'fecha_registro' => 'Fecha de Registro',
        ];
    }

    public function search($params)
    {
        $query = User::find();

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
              ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
} 