<?php

namespace app\controllers;

use Yii;
use app\models\Usuario;
use app\models\UsuarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class UsuarioController extends Controller
{
    public function behaviors()
    {
        return [
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
        $searchModel = new UsuarioSearch();
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
        $model = new Usuario();

        if ($model->load(Yii::$app->request->post())) {
            // Procesar la imagen subida antes de guardar el modelo
            if ($model->upload() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Usuario creado correctamente');
                return $this->redirect(['view', 'id' => $model->id_usuario]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldPassword = $model->contrasena;
        $oldImage = $model->imagen_perfil;

        if ($model->load(Yii::$app->request->post())) {
            // Si no se proporciona contraseña en actualización, mantener la anterior
            if (empty($model->contrasena)) {
                $model->contrasena = $oldPassword;
            }
            
            // Procesar la imagen subida antes de guardar el modelo
            if ($model->upload() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Usuario actualizado correctamente');
                return $this->redirect(['view', 'id' => $model->id_usuario]);
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
        if ($model->imagen_perfil) {
            \app\components\UploadHandler::deleteImage($model->imagen_perfil, 'usuarios');
        }
        
        $model->delete();
        Yii::$app->session->setFlash('success', 'Usuario eliminado correctamente');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Usuario::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
} 