<?php

namespace app\controllers;

use Yii;
use app\models\Libro;
use app\models\LibroSearch;
use app\models\Categoria;
use app\models\Autor;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * CatalogoController implementa las acciones relacionadas al catálogo de libros público
 */
class CatalogoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['view', 'categoria', 'autor'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'categoria', 'autor'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * Muestra el catálogo de libros
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LibroSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 12;
        
        $categorias = Categoria::find()->all();
        $autores = Autor::find()->all();
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categorias' => $categorias,
            'autores' => $autores,
        ]);
    }
    
    /**
     * Muestra los detalles de un libro
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el libro no es encontrado
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        // Buscar libros relacionados (misma categoría)
        $relacionados = Libro::find()
            ->where(['id_categoria' => $model->id_categoria])
            ->andWhere(['<>', 'id_libro', $model->id_libro])
            ->limit(4)
            ->all();
        
        return $this->render('view', [
            'model' => $model,
            'relacionados' => $relacionados,
        ]);
    }
    
    /**
     * Muestra libros por categoría
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si la categoría no es encontrada
     */
    public function actionCategoria($id)
    {
        $categoria = Categoria::findOne($id);
        if (!$categoria) {
            throw new NotFoundHttpException('La categoría solicitada no existe.');
        }
        
        $query = Libro::find()->where(['id_categoria' => $id])->orderBy(['titulo' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);
        
        return $this->render('categoria', [
            'dataProvider' => $dataProvider,
            'categoria' => $categoria,
        ]);
    }
    
    /**
     * Muestra libros por autor
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el autor no es encontrado
     */
    public function actionAutor($id)
    {
        $autor = Autor::findOne($id);
        if (!$autor) {
            throw new NotFoundHttpException('El autor solicitado no existe.');
        }
        
        $query = Libro::find()->where(['id_autor' => $id])->orderBy(['titulo' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);
        
        return $this->render('autor', [
            'dataProvider' => $dataProvider,
            'autor' => $autor,
        ]);
    }
    
    /**
     * Encuentra el modelo Libro basado en su clave primaria.
     * Si el modelo no es encontrado, se lanzará una excepción 404 HTTP.
     * @param integer $id
     * @return Libro el modelo cargado
     * @throws NotFoundHttpException si el modelo no es encontrado
     */
    protected function findModel($id)
    {
        if (($model = Libro::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El libro solicitado no existe.');
    }
}
