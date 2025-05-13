<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * UsuarioController implementa las acciones CRUD para el modelo User.
 */
class UsuarioController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['view', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $id = Yii::$app->request->get('id_usuario');
                            // Permitir acceso si es el propio usuario o es administrador
                            return $id == Yii::$app->user->identity->id_usuario || 
                                   Yii::$app->user->identity->id_rol === 1;
                        }
                    ],
                    [
                        'actions' => ['index', 'create', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            // Solo permitir administradores para estas acciones
                            return Yii::$app->user->identity->id_rol === 1;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lista todos los usuarios.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra un usuario específico.
     * @param integer $id_usuario
     * @return mixed
     * @throws NotFoundHttpException si el usuario no existe
     */
    public function actionView($id_usuario)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_usuario),
        ]);
    }

    /**
     * Crea un nuevo usuario.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = User::SCENARIO_CREATE;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->upload() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Usuario creado correctamente');
                return $this->redirect(['view', 'id_usuario' => $model->id_usuario]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Actualiza un usuario existente.
     * @param integer $id_usuario
     * @return mixed
     * @throws NotFoundHttpException si el usuario no existe
     */
    public function actionUpdate($id_usuario = null)
    {
        // Si no se proporciona id_usuario, usar el ID del usuario actual
        if ($id_usuario === null) {
            $id_usuario = Yii::$app->user->identity->id_usuario;
        }

        $model = $this->findModel($id_usuario);
        
        // Si el usuario no es administrador, solo puede modificar ciertos campos
        if (Yii::$app->user->identity->id_rol !== 1) {
            $model->scenario = User::SCENARIO_SELF_UPDATE;
        } else {
            $model->scenario = User::SCENARIO_UPDATE;
        }

        if ($model->load(Yii::$app->request->post())) {
            // Si se proporciona una nueva contraseña, actualizarla
            if (!empty($model->password)) {
                $model->setPassword($model->password);
            }
            
            if ($model->upload() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Usuario actualizado correctamente');
                return $this->redirect(['view', 'id_usuario' => $model->id_usuario]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Elimina un usuario existente.
     * @param integer $id_usuario
     * @return mixed
     * @throws NotFoundHttpException si el usuario no existe
     */
    public function actionDelete($id_usuario)
    {
        $model = $this->findModel($id_usuario);
        $model->status = User::STATUS_DELETED;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Usuario eliminado correctamente');
        }

        return $this->redirect(['index']);
    }

    /**
     * Encuentra el modelo User basado en su clave primaria.
     * @param integer $id_usuario
     * @return User el modelo cargado
     * @throws NotFoundHttpException si el modelo no se encuentra
     */
    protected function findModel($id_usuario)
    {
        if (($model = User::findOne($id_usuario)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El usuario solicitado no existe.');
    }
} 