<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UsuarioProfesor;

/**
 * UsuarioProfesorSearch represents the model behind the search form of `app\models\UsuarioProfesor`.
 */
class UsuarioProfesorSearch extends UsuarioProfesor
{
    public $username;
    public $nombre;
    public $apellido;
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_profesor', 'id_usuario', 'created_at', 'updated_at'], 'integer'],
            [['especialidad', 'departamento', 'oficina', 'username', 'nombre', 'apellido', 'email'], 'safe'],
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
        $query = UsuarioProfesor::find()
            ->joinWith('usuario');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id_profesor' => SORT_DESC,
                ],
                'attributes' => [
                    'id_profesor',
                    'especialidad',
                    'departamento',
                    'oficina',
                    'created_at',
                    'username' => [
                        'asc' => ['usuarios.username' => SORT_ASC],
                        'desc' => ['usuarios.username' => SORT_DESC],
                    ],
                    'nombre' => [
                        'asc' => ['usuarios.nombre' => SORT_ASC],
                        'desc' => ['usuarios.nombre' => SORT_DESC],
                    ],
                    'apellido' => [
                        'asc' => ['usuarios.apellido' => SORT_ASC],
                        'desc' => ['usuarios.apellido' => SORT_DESC],
                    ],
                    'email' => [
                        'asc' => ['usuarios.email' => SORT_ASC],
                        'desc' => ['usuarios.email' => SORT_DESC],
                    ],
                ],
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
            'id_profesor' => $this->id_profesor,
            'id_usuario' => $this->id_usuario,
            'usuarios_profesores.created_at' => $this->created_at,
            'usuarios_profesores.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'especialidad', $this->especialidad])
            ->andFilterWhere(['like', 'departamento', $this->departamento])
            ->andFilterWhere(['like', 'oficina', $this->oficina])
            ->andFilterWhere(['like', 'usuarios.username', $this->username])
            ->andFilterWhere(['like', 'usuarios.nombre', $this->nombre])
            ->andFilterWhere(['like', 'usuarios.apellido', $this->apellido])
            ->andFilterWhere(['like', 'usuarios.email', $this->email]);

        return $dataProvider;
    }
}
