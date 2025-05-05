<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class LibroSearch extends Libro
{
    public function rules()
    {
        return [
            [['id_libro', 'id_autor', 'id_categoria', 'anio_publicacion'], 'integer'],
            [['titulo'], 'safe'],
            [['disponible'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_libro' => 'ID',
            'titulo' => 'Título',
            'id_autor' => 'Autor',
            'id_categoria' => 'Categoría',
            'anio_publicacion' => 'Año de Publicación',
            'disponible' => 'Disponible',
        ];
    }

    public function search($params)
    {
        $query = Libro::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'titulo' => SORT_ASC,
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
            'id_libro' => $this->id_libro,
            'id_autor' => $this->id_autor,
            'id_categoria' => $this->id_categoria,
            'anio_publicacion' => $this->anio_publicacion,
            'disponible' => $this->disponible,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo]);

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