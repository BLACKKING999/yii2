<?php

namespace app\controllers;

use Yii;
use app\models\Autor;
use app\models\AutorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AutorController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return !Yii::$app->user->isGuest && Yii::$app->user->identity->puedeAdministrarLibros();
                        },
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

    public function actionIndex()
    {
        $searchModel = new AutorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Autor();

        if ($model->load(Yii::$app->request->post())) {
            // Procesar la imagen subida antes de guardar el modelo
            if ($model->upload() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Autor creado correctamente');
                return $this->redirect(['view', 'id' => $model->id_autor]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldImage = $model->imagen_autor;

        if ($model->load(Yii::$app->request->post())) {
            // Procesar la imagen subida antes de guardar el modelo
            if ($model->upload() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Autor actualizado correctamente');
                return $this->redirect(['view', 'id' => $model->id_autor]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        // Eliminar la imagen asociada si existe
        if ($model->imagen_autor) {
            \app\components\UploadHandler::deleteImage($model->imagen_autor, 'autores');
        }
        
        $model->delete();
        Yii::$app->session->setFlash('success', 'Autor eliminado correctamente');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Autor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
} 