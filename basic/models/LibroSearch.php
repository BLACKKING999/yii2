<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * LibroSearch representa el modelo detrás del formulario de búsqueda de `app\models\Libro`.
 */
class LibroSearch extends Libro
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_libro', 'id_autor', 'id_categoria', 'anio_publicacion', 'num_paginas'], 'integer'],
            [['isbn', 'titulo', 'editorial', 'idioma', 'ubicacion_fisica', 'created_at', 'updated_at'], 'safe'],
            [['disponible'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Libro::find()->with(['autor', 'categoria']);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id_libro' => SORT_DESC],
                'attributes' => [
                    'id_libro',
                    'isbn',
                    'titulo',
                    'editorial',
                    'anio_publicacion',
                    'num_paginas',
                    'idioma',
                    'ubicacion_fisica',
                    'created_at',
                    'updated_at',
                    'disponible',
                    'id_autor' => [
                        'asc' => ['autor.nombre_autor' => SORT_ASC],
                        'desc' => ['autor.nombre_autor' => SORT_DESC],
                    ],
                    'id_categoria' => [
                        'asc' => ['categoria.nombre_categoria' => SORT_ASC],
                        'desc' => ['categoria.nombre_categoria' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_libro' => $this->id_libro,
            'id_autor' => $this->id_autor,
            'id_categoria' => $this->id_categoria,
            'anio_publicacion' => $this->anio_publicacion,
            'num_paginas' => $this->num_paginas,
            'disponible' => $this->disponible,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'isbn', $this->isbn])
            ->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'editorial', $this->editorial])
            ->andFilterWhere(['like', 'idioma', $this->idioma])
            ->andFilterWhere(['like', 'ubicacion_fisica', $this->ubicacion_fisica]);

        return $dataProvider;
    }

    public static function getAutoresList()
    {
        return ArrayHelper::map(Autor::find()->all(), 'id_autor', 'nombre_autor');
    }

    public static function getCategoriasList()
    {
        return ArrayHelper::map(Categoria::find()->all(), 'id_categoria', 'nombre_categoria');
    }

    public static function getAniosList()
    {
        $anioActual = date('Y');
        $anios = range($anioActual - 100, $anioActual);
        return array_combine($anios, $anios);
    }
} 