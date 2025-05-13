<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UsuarioProfesor;

/**
 * UsuarioProfesorSearch representa el modelo detrás del formulario de búsqueda de `app\models\UsuarioProfesor`.
 */
class UsuarioProfesorSearch extends UsuarioProfesor
{
    /**
     * Propiedades para búsqueda en User
     */
    public $username;
    public $nombre;
    public $apellidos;
    public $email;
    public $especialidad;
    public $departamento;
    public $oficina;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_profesor', 'id_usuario', 'created_at', 'updated_at', 'id_usuario_biblioteca'], 'integer'],
            [['especialidad', 'departamento', 'oficina', 'username', 'nombre', 'apellidos', 'email'], 'safe'],
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
            'especialidad' => 'Especialidad',
            'departamento' => 'Departamento',
            'oficina' => 'Oficina',
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
        $query = UsuarioProfesor::find()
            ->select([
                'usuarios_profesores.*',
                'usuarios.username',
                'usuarios.nombre',
                'usuarios.apellidos',
                'usuarios.email'
            ])
            ->joinWith(['user']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id_profesor' => SORT_DESC],
                'attributes' => [
                    'id_profesor',
                    'id_usuario',
                    'especialidad',
                    'departamento',
                    'oficina',
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

        // Filtros para campos de UsuarioProfesor
        $query->andFilterWhere([
            'usuarios_profesores.id_profesor' => $this->id_profesor,
            'usuarios_profesores.id_usuario' => $this->id_usuario,
            'usuarios_profesores.created_at' => $this->created_at,
            'usuarios_profesores.updated_at' => $this->updated_at,
            'usuarios_profesores.id_usuario_biblioteca' => $this->id_usuario_biblioteca,
        ]);

        $query->andFilterWhere(['like', 'usuarios_profesores.especialidad', $this->especialidad])
            ->andFilterWhere(['like', 'usuarios_profesores.departamento', $this->departamento])
            ->andFilterWhere(['like', 'usuarios_profesores.oficina', $this->oficina]);

        // Filtros para campos de User
        $query->andFilterWhere(['like', 'usuarios.username', $this->username])
            ->andFilterWhere(['like', 'usuarios.nombre', $this->nombre])
            ->andFilterWhere(['like', 'usuarios.apellidos', $this->apellidos])
            ->andFilterWhere(['like', 'usuarios.email', $this->email]);

        return $dataProvider;
    }
}
