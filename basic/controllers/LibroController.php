<?php

namespace app\controllers;

use Yii;
use app\models\Libro;
use app\models\LibroSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

/**
 * LibroController implementa las acciones CRUD para el modelo Libro.
 */
class LibroController extends Controller
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
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->puedeAdministrarLibros();
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
     * Lista todos los libros.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LibroSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra un libro específico.
     * @param integer $id_libro
     * @return mixed
     * @throws NotFoundHttpException si el libro no existe
     */
    public function actionView($id_libro)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_libro),
        ]);
    }

    /**
     * Crea un nuevo libro.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Libro();

        if ($model->load(Yii::$app->request->post())) {
            $model->imagenFile = UploadedFile::getInstance($model, 'imagenFile');
            
            if ($model->upload() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Libro creado exitosamente.');
                return $this->redirect(['view', 'id_libro' => $model->id_libro]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Actualiza un libro existente.
     * @param integer $id_libro
     * @return mixed
     * @throws NotFoundHttpException si el libro no existe
     */
    public function actionUpdate($id_libro)
    {
        $model = $this->findModel($id_libro);

        if ($model->load(Yii::$app->request->post())) {
            $imagenFile = UploadedFile::getInstance($model, 'imagenFile');
            
            if ($imagenFile) {
                $model->imagenFile = $imagenFile;
                if (!$model->upload()) {
                    Yii::$app->session->setFlash('error', 'Error al subir la imagen.');
                    return $this->render('update', ['model' => $model]);
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Libro actualizado exitosamente.');
                return $this->redirect(['view', 'id_libro' => $model->id_libro]);
            } else {
                Yii::$app->session->setFlash('error', 'Error al actualizar el libro: ' . implode(', ', $model->getFirstErrors()));
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Elimina un libro existente.
     * @param integer $id_libro
     * @return mixed
     * @throws NotFoundHttpException si el libro no existe
     */
    public function actionDelete($id_libro)
    {
        $model = $this->findModel($id_libro);
        
        // Eliminar la imagen de portada si existe
        if ($model->imagen_portada) {
            $imagenPath = Yii::getAlias('@webroot/uploads/libros/') . $model->imagen_portada;
            if (file_exists($imagenPath)) {
                unlink($imagenPath);
            }
        }
        
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Libro eliminado exitosamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Error al eliminar el libro.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Encuentra el modelo Libro basado en su clave primaria.
     * @param integer $id_libro
     * @return Libro el modelo cargado
     * @throws NotFoundHttpException si el modelo no se encuentra
     */
    protected function findModel($id_libro)
    {
        if (($model = Libro::findOne($id_libro)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El libro solicitado no existe.');
    }
} 