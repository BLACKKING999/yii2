<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UsuarioPersonal;

/**
 * UsuarioPersonalSearch representa el modelo detrás del formulario de búsqueda de `app\models\UsuarioPersonal`.
 */
class UsuarioPersonalSearch extends UsuarioPersonal
{
    /**
     * Propiedades para búsqueda en User
     */
    public $username;
    public $nombre;
    public $apellidos;
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_personal', 'id_usuario', 'created_at', 'updated_at'], 'integer'],
            [['departamento', 'cargo', 'fecha_contratacion'], 'safe'],
            [['username', 'nombre', 'apellidos', 'email'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'username' => 'Usuario',
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'email' => 'Correo electrónico',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Crea una instancia de proveedor de datos con la consulta de búsqueda aplicada
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UsuarioPersonal::find()
            ->select([
                'usuarios_personal.*',
                'usuarios.username',
                'usuarios.nombre',
                'usuarios.apellidos',
                'usuarios.email'
            ])
            ->joinWith(['user']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id_personal' => SORT_DESC],
                'attributes' => [
                    'id_personal',
                    'id_usuario',
                    'departamento',
                    'cargo',
                    'fecha_contratacion',
                    'created_at',
                    'updated_at',
                    'username' => [
                        'asc' => ['usuarios.username' => SORT_ASC],
                        'desc' => ['usuarios.username' => SORT_DESC],
                    ],
                    'nombre' => [
                        'asc' => ['usuarios.nombre' => SORT_ASC],
                        'desc' => ['usuarios.nombre' => SORT_DESC],
                    ],
                    'apellidos' => [
                        'asc' => ['usuarios.apellidos' => SORT_ASC],
                        'desc' => ['usuarios.apellidos' => SORT_DESC],
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
            return $dataProvider;
        }

        // Filtros para campos de UsuarioPersonal
        $query->andFilterWhere([
            'usuarios_personal.id_personal' => $this->id_personal,
            'usuarios_personal.id_usuario' => $this->id_usuario,
            'usuarios_personal.fecha_contratacion' => $this->fecha_contratacion,
            'usuarios_personal.created_at' => $this->created_at,
            'usuarios_personal.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'usuarios_personal.departamento', $this->departamento])
            ->andFilterWhere(['like', 'usuarios_personal.cargo', $this->cargo]);

        // Filtros para campos de User
        $query->andFilterWhere(['like', 'usuarios.username', $this->username])
            ->andFilterWhere(['like', 'usuarios.nombre', $this->nombre])
            ->andFilterWhere(['like', 'usuarios.apellidos', $this->apellidos])
            ->andFilterWhere(['like', 'usuarios.email', $this->email]);

        return $dataProvider;
    }
}
