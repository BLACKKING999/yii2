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
 * UsuarioPersonalController implementa las acciones CRUD para el modelo UsuarioPersonal.
 */
class UsuarioPersonalController extends Controller
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
                            return Yii::$app->user->identity && Yii::$app->user->identity->puedeAdministrarUsuarios();
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
     * Lista todos los modelos UsuarioPersonal.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsuarioPersonalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        // Cargar la relación con el usuario
        $dataProvider->query->with(['user']);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra un único modelo UsuarioPersonal.
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
     * Crea un nuevo modelo UsuarioPersonal.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UsuarioPersonal();
        $userModel = new \app\models\User();

        if ($model->load(Yii::$app->request->post()) && $userModel->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($userModel->save()) {
                    $model->id_usuario = $userModel->id_usuario;
                    if ($model->save()) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id_personal]);
                    }
                }
                $transaction->rollBack();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $this->render('create', [
            'model' => $model,
            'userModel' => $userModel,
        ]);
    }

    /**
     * Actualiza un modelo UsuarioPersonal existente.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no se encuentra
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $userModel = $model->user;

        if ($model->load(Yii::$app->request->post()) && $userModel->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($userModel->save() && $model->save()) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id_personal]);
                }
                $transaction->rollBack();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $this->render('update', [
            'model' => $model,
            'userModel' => $userModel,
        ]);
    }

    /**
     * Elimina un modelo UsuarioPersonal existente.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no se encuentra
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $userModel = $model->user;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->delete() && $userModel->delete()) {
                $transaction->commit();
                return $this->redirect(['index']);
            }
            $transaction->rollBack();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
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
