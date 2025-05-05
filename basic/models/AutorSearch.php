<?php

namespace app\models;

use yii\data\ActiveDataProvider;

class AutorSearch extends Autor
{
    public function rules()
    {
        return [
            [['id_autor'], 'integer'],
            [['nombre_autor', 'nacionalidad'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_autor' => 'ID',
            'nombre_autor' => 'Nombre del Autor',
            'nacionalidad' => 'Nacionalidad',
        ];
    }

    public function search($params)
    {
        $query = Autor::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'nombre_autor' => SORT_ASC,
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
            'id_autor' => $this->id_autor,
        ]);

        $query->andFilterWhere(['like', 'nombre_autor', $this->nombre_autor])
              ->andFilterWhere(['like', 'nacionalidad', $this->nacionalidad]);

        return $dataProvider;
    }
} 