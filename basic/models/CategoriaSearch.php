<?php

namespace app\models;

use yii\data\ActiveDataProvider;

class CategoriaSearch extends Categoria
{
    public function rules()
    {
        return [
            [['id_categoria'], 'integer'],
            [['nombre_categoria'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_categoria' => 'ID',
            'nombre_categoria' => 'Nombre de la Categoría',
        ];
    }

    public function search($params)
    {
        $query = Categoria::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'nombre_categoria' => SORT_ASC,
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
            'id_categoria' => $this->id_categoria,
        ]);

        $query->andFilterWhere(['like', 'nombre_categoria', $this->nombre_categoria]);

        return $dataProvider;
    }
} 