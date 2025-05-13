<?php

namespace app\controllers;

use Yii;
use app\models\UsuarioPersonal;
use app\models\UsuarioPersonalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PersonalController implementa las acciones CRUD para el modelo UsuarioPersonal.
 */
class PersonalController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->puedeAdministrarUsuarios();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lista todos los registros de personal.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsuarioPersonalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra un registro de personal específico.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no se encuentra
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Crea un nuevo registro de personal.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UsuarioPersonal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Personal creado exitosamente.');
            return $this->redirect(['view', 'id' => $model->id_personal]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Actualiza un registro de personal existente.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no se encuentra
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Personal actualizado exitosamente.');
            return $this->redirect(['view', 'id' => $model->id_personal]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Elimina un registro de personal existente.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no se encuentra
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Personal eliminado exitosamente.');

        return $this->redirect(['index']);
    }

    /**
     * Encuentra el modelo UsuarioPersonal basado en su valor de clave primaria.
     * @param integer $id
     * @return UsuarioPersonal el modelo cargado
     * @throws NotFoundHttpException si el modelo no se encuentra
     */
    protected function findModel($id)
    {
        if (($model = UsuarioPersonal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El personal solicitado no existe.');
    }
} 