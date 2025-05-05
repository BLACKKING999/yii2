<?php

namespace app\controllers;

use Yii;
use app\models\Libro;
use app\models\LibroSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\caching\DbDependency;

/**
 * LibroController implements the CRUD actions for Libro model.
 */
class LibroController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * Lists all Libro models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LibroSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Usar el método optimizado para obtener los libros
        $libros = Libro::getAllWithRelations();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'libros' => $libros,
        ]);
    }

    /**
     * Displays a single Libro model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $cacheKey = 'libro_' . $id;
        $cache = Yii::$app->cache;
        
        $model = $cache->getOrSet($cacheKey, function () use ($id) {
            return $this->findModel($id);
        }, 3600);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Libro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Libro();

        if ($model->load(Yii::$app->request->post())) {
            // Procesar la imagen subida antes de guardar el modelo
            if ($model->upload() && $model->save()) {
                // Limpiar el caché después de crear un nuevo libro
                Yii::$app->cache->delete('libros_with_relations');
                Yii::$app->session->setFlash('success', 'Libro creado correctamente');
                return $this->redirect(['view', 'id' => $model->id_libro]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Libro model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldImage = $model->imagen_portada;

        if ($model->load(Yii::$app->request->post())) {
            // Procesar la imagen subida antes de guardar el modelo
            if ($model->upload() && $model->save()) {
                // Limpiar el caché después de actualizar
                Yii::$app->cache->delete('libros_with_relations');
                Yii::$app->cache->delete('libro_' . $id);
                Yii::$app->session->setFlash('success', 'Libro actualizado correctamente');
                return $this->redirect(['view', 'id' => $model->id_libro]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Libro model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        // Eliminar la imagen asociada si existe
        if ($model->imagen_portada) {
            \app\components\UploadHandler::deleteImage($model->imagen_portada, 'libros');
        }
        
        $model->delete();
        // Limpiar el caché después de eliminar
        Yii::$app->cache->delete('libros_with_relations');
        Yii::$app->cache->delete('libro_' . $id);
        Yii::$app->session->setFlash('success', 'Libro eliminado correctamente');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Libro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Libro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Libro::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
} 