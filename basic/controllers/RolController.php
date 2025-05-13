<?php

namespace app\controllers;

use Yii;
use app\models\Rol;
use app\models\RolSearch;
use app\models\Permiso;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * RolController implementa las acciones CRUD para el modelo Rol.
 */
class RolController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'asignar-permisos'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity && Yii::$app->user->identity->puedeAdministrarRoles();
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
     * Lista todos los modelos Rol.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RolSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra un modelo Rol específico.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no es encontrado
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $permisos = $model->getPermisos()->all();
        
        return $this->render('view', [
            'model' => $model,
            'permisos' => $permisos,
        ]);
    }

    /**
     * Crea un nuevo modelo Rol.
     * Si se crea correctamente, el navegador será redirigido a la página 'view'.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rol();
        $model->created_at = time();
        $model->updated_at = time();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Rol creado correctamente.');
            return $this->redirect(['view', 'id' => $model->id_rol]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Actualiza un modelo Rol existente.
     * Si se actualiza correctamente, el navegador será redirigido a la página 'view'.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no es encontrado
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->updated_at = time();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Rol actualizado correctamente.');
            return $this->redirect(['view', 'id' => $model->id_rol]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Elimina un modelo Rol existente.
     * Si se elimina correctamente, el navegador será redirigido a la página 'index'.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no es encontrado
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        // No permitir eliminar roles predeterminados del sistema
        $model = $this->findModel($id);
        
        if ($id <= 5) {
            Yii::$app->session->setFlash('error', 'No se puede eliminar un rol predeterminado del sistema.');
            return $this->redirect(['index']);
        }
        
        try {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Rol eliminado correctamente.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Error al eliminar el rol: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }
    
    /**
     * Asigna permisos a un rol.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no es encontrado
     */
    public function actionAsignarPermisos($id)
    {
        $model = $this->findModel($id);
        $allPermisos = Permiso::find()->all();
        $permisosAsignados = ArrayHelper::map($model->getPermisos()->all(), 'id_permiso', 'id_permiso');
        
        if (Yii::$app->request->isPost) {
            $permisosSeleccionados = Yii::$app->request->post('permisos', []);
            
            // Eliminar los permisos que ya no están seleccionados
            foreach ($permisosAsignados as $idPermiso) {
                if (!in_array($idPermiso, $permisosSeleccionados)) {
                    $model->revocarPermiso($idPermiso);
                }
            }
            
            // Agregar los nuevos permisos seleccionados
            foreach ($permisosSeleccionados as $idPermiso) {
                if (!isset($permisosAsignados[$idPermiso])) {
                    $model->asignarPermiso($idPermiso);
                }
            }
            
            Yii::$app->session->setFlash('success', 'Permisos asignados correctamente.');
            return $this->redirect(['view', 'id' => $model->id_rol]);
        }
        
        return $this->render('asignar-permisos', [
            'model' => $model,
            'allPermisos' => $allPermisos,
            'permisosAsignados' => $permisosAsignados,
        ]);
    }

    /**
     * Busca el modelo Rol basado en su clave primaria.
     * Si el modelo no es encontrado, se lanzará una excepción 404 HTTP.
     * @param integer $id
     * @return Rol el modelo cargado
     * @throws NotFoundHttpException si el modelo no es encontrado
     */
    protected function findModel($id)
    {
        if (($model = Rol::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
