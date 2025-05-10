<?php

namespace app\controllers;

use Yii;
use app\models\UsuarioProfesor;
use app\models\UsuarioProfesorSearch;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * UsuarioProfesorController implementa las acciones CRUD para el modelo UsuarioProfesor.
 */
class UsuarioProfesorController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->puedeAdministrarUsuarios();
                        }
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
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
     * Lista todos los modelos UsuarioProfesor.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsuarioProfesorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra un modelo UsuarioProfesor específico.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no es encontrado
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Crea un nuevo modelo UsuarioProfesor.
     * Si se crea correctamente, el navegador será redirigido a la página 'view'.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UsuarioProfesor();
        $userModel = new User();
        $userModel->scenario = User::SCENARIO_CREATE;

        if ($userModel->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post())) {
            
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Guardar el usuario primero
                $userModel->setPassword($userModel->password);
                $userModel->generateAuthKey();
                
                if (!$userModel->save()) {
                    throw new \Exception('Error al guardar el usuario: ' . implode(', ', $userModel->getErrorSummary(true)));
                }
                
                // Asignar el id_usuario al profesor
                $model->id_usuario = $userModel->id_usuario;
                
                if (!$model->save()) {
                    throw new \Exception('Error al guardar el profesor: ' . implode(', ', $model->getErrorSummary(true)));
                }
                
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Profesor creado correctamente.');
                return $this->redirect(['view', 'id' => $model->id_profesor]);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'userModel' => $userModel,
        ]);
    }

    /**
     * Actualiza un modelo UsuarioProfesor existente.
     * Si se actualiza correctamente, el navegador será redirigido a la página 'view'.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no es encontrado
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $userModel = User::findOne($model->id_usuario);
        $userModel->scenario = User::SCENARIO_UPDATE;

        if ($userModel->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post())) {
            
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Actualizar contraseña solo si se proporciona una nueva
                if (!empty($userModel->password)) {
                    $userModel->setPassword($userModel->password);
                }
                
                if (!$userModel->save()) {
                    throw new \Exception('Error al actualizar el usuario: ' . implode(', ', $userModel->getErrorSummary(true)));
                }
                
                if (!$model->save()) {
                    throw new \Exception('Error al actualizar el profesor: ' . implode(', ', $model->getErrorSummary(true)));
                }
                
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Profesor actualizado correctamente.');
                return $this->redirect(['view', 'id' => $model->id_profesor]);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'userModel' => $userModel,
        ]);
    }

    /**
     * Elimina un modelo UsuarioProfesor existente.
     * Si se elimina correctamente, el navegador será redirigido a la página 'index'.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no es encontrado
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $userId = $model->id_usuario;
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->delete();
            
            // Eliminar también el usuario asociado
            $user = User::findOne($userId);
            if ($user) {
                $user->delete();
            }
            
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Profesor eliminado correctamente.');
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Error al eliminar el profesor: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Busca el modelo UsuarioProfesor basado en su clave primaria.
     * Si el modelo no es encontrado, se lanzará una excepción 404 HTTP.
     * @param integer $id
     * @return UsuarioProfesor el modelo cargado
     * @throws NotFoundHttpException si el modelo no es encontrado
     */
    protected function findModel($id)
    {
        if (($model = UsuarioProfesor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
