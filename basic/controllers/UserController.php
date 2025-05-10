<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Usuario;
use app\models\PasswordChangeForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implementa las acciones CRUD para el modelo User.
 */
class UserController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'reset-password'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin();
                        }
                    ],
                    [
                        'actions' => ['change-password', 'view', 'update', 'vincular-usuario'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if ($action->id === 'view' || $action->id === 'update') {
                                // Solo permite ver o actualizar su propio perfil
                                $id = Yii::$app->request->get('id');
                                return $id == Yii::$app->user->id;
                            }
                            return true;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'reset-password' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Muestra una lista de todos los modelos User.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra un modelo User específico.
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
     * Crea un nuevo modelo User.
     * Si se crea exitosamente, el navegador redireccionará a la página 'view'.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->status = User::STATUS_ACTIVE;

        if ($model->load(Yii::$app->request->post())) {
            // Generamos contraseña y claves de autenticación
            if (!empty($model->password)) {
                $model->setPassword($model->password);
            }
            $model->generateAuthKey();
            $model->generateAccessToken();
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Usuario creado correctamente.');
                return $this->redirect(['view', 'id' => $model->id_usuario]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Actualiza un modelo User existente.
     * Si se actualiza exitosamente, el navegador redireccionará a la página 'view'.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no se encuentra
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // Si hay una contraseña nueva, establecerla
            if (!empty($model->password)) {
                $model->setPassword($model->password);
            }
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Usuario actualizado correctamente.');
                return $this->redirect(['view', 'id' => $model->id_usuario]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Elimina un modelo User existente.
     * Si se elimina exitosamente, el navegador redireccionará a la página 'index'.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no se encuentra
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'Usuario eliminado correctamente.');
        return $this->redirect(['index']);
    }

    /**
     * Permite a los usuarios cambiar su propia contraseña.
     * @return mixed
     */
    public function actionChangePassword()
    {
        $model = new PasswordChangeForm();

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->session->setFlash('success', 'Contraseña cambiada correctamente.');
            return $this->redirect(['/site/index']);
        }

        return $this->render('change-password', [
            'model' => $model,
        ]);
    }

    /**
     * Permite a los administradores restablecer la contraseña de cualquier usuario.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no se encuentra
     */
    public function actionResetPassword($id)
    {
        $user = $this->findModel($id);
        $newPassword = Yii::$app->security->generateRandomString(8);
        $user->password = $newPassword;
        
        if ($user->save()) {
            Yii::$app->session->setFlash('success', "Contraseña restablecida. Nueva contraseña: $newPassword");
        } else {
            Yii::$app->session->setFlash('error', 'Error al restablecer la contraseña.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }
    
    /**
     * Permite vincular un usuario del sistema con un usuario de la biblioteca
     * @param integer $id ID del usuario del sistema
     * @return mixed
     * @throws NotFoundHttpException si el modelo no se encuentra
     */
    public function actionVincularUsuario($id)
    {
        $user = $this->findModel($id);
        $usuarios = Usuario::find()->all();
        
        if (Yii::$app->request->isPost) {
            $id_usuario_biblioteca = Yii::$app->request->post('id_usuario_biblioteca');
            if ($id_usuario_biblioteca) {
                if ($user->vincularUsuarioBiblioteca($id_usuario_biblioteca)) {
                    Yii::$app->session->setFlash('success', 'Usuario de sistema vinculado correctamente con usuario de biblioteca.');
                } else {
                    Yii::$app->session->setFlash('error', 'Error al vincular usuarios.');
                }
                return $this->redirect(['view', 'id' => $id]);
            }
        }
        
        return $this->render('vincular-usuario', [
            'model' => $user,
            'usuarios' => $usuarios,
        ]);
    }

    /**
     * Encuentra el modelo User basado en el valor de su llave primaria.
     * Si el modelo no se encuentra, se lanza una excepción 404 HTTP.
     * @param integer $id
     * @return User el modelo cargado
     * @throws NotFoundHttpException si el modelo no se encuentra
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id_usuario' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
