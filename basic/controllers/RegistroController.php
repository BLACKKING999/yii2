<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UsuarioEstudiante;
use app\models\UsuarioProfesor;
use app\models\UsuarioPersonal;
use app\models\Rol;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * RegistroController implementa las acciones para el registro de nuevos usuarios
 * en la base de datos normalizada.
 */
class RegistroController extends Controller
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
                        'actions' => ['index', 'estudiante', 'profesor', 'personal'],
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
     * Muestra la página principal de registro con opciones para los diferentes tipos de usuarios.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Registra un nuevo usuario estudiante.
     * @return mixed
     */
    public function actionEstudiante()
    {
        $user = new User();
        $user->status = User::STATUS_ACTIVE;
        $estudiante = new UsuarioEstudiante();

        if ($user->load(Yii::$app->request->post()) && $estudiante->load(Yii::$app->request->post())) {
            // Iniciar transacción
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                // Configurar y guardar usuario
                if (!empty($user->password)) {
                    $user->setPassword($user->password);
                }
                $user->generateAuthKey();
                $user->generateAccessToken();
                
                if ($user->save()) {
                    // Vincular estudiante con usuario
                    $estudiante->id_usuario = $user->id_usuario;
                    
                    if ($estudiante->save()) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Usuario estudiante creado correctamente.');
                        return $this->redirect(['user/view', 'id' => $user->id_usuario]);
                    } else {
                        $transaction->rollBack();
                    }
                } else {
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Error al crear el usuario: ' . $e->getMessage());
            }
        }

        $roles = ArrayHelper::map(Rol::find()->orderBy(['nivel_acceso' => SORT_DESC])->all(), 'id_rol', 'nombre_rol');
        
        return $this->render('estudiante', [
            'user' => $user,
            'estudiante' => $estudiante,
            'roles' => $roles,
        ]);
    }

    /**
     * Registra un nuevo usuario profesor.
     * @return mixed
     */
    public function actionProfesor()
    {
        $user = new User();
        $user->status = User::STATUS_ACTIVE;
        $profesor = new UsuarioProfesor();

        if ($user->load(Yii::$app->request->post()) && $profesor->load(Yii::$app->request->post())) {
            // Iniciar transacción
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                // Configurar y guardar usuario
                if (!empty($user->password)) {
                    $user->setPassword($user->password);
                }
                $user->generateAuthKey();
                $user->generateAccessToken();
                
                if ($user->save()) {
                    // Vincular profesor con usuario
                    $profesor->id_usuario = $user->id_usuario;
                    
                    if ($profesor->save()) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Usuario profesor creado correctamente.');
                        return $this->redirect(['user/view', 'id' => $user->id_usuario]);
                    } else {
                        $transaction->rollBack();
                    }
                } else {
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Error al crear el usuario: ' . $e->getMessage());
            }
        }

        $roles = ArrayHelper::map(Rol::find()->orderBy(['nivel_acceso' => SORT_DESC])->all(), 'id_rol', 'nombre_rol');
        
        return $this->render('profesor', [
            'user' => $user,
            'profesor' => $profesor,
            'roles' => $roles,
        ]);
    }

    /**
     * Registra un nuevo usuario de personal administrativo.
     * @return mixed
     */
    public function actionPersonal()
    {
        $user = new User();
        $user->status = User::STATUS_ACTIVE;
        $personal = new UsuarioPersonal();

        if ($user->load(Yii::$app->request->post()) && $personal->load(Yii::$app->request->post())) {
            // Iniciar transacción
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                // Configurar y guardar usuario
                if (!empty($user->password)) {
                    $user->setPassword($user->password);
                }
                $user->generateAuthKey();
                $user->generateAccessToken();
                
                if ($user->save()) {
                    // Vincular personal con usuario
                    $personal->id_usuario = $user->id_usuario;
                    
                    if ($personal->save()) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Usuario de personal creado correctamente.');
                        return $this->redirect(['user/view', 'id' => $user->id_usuario]);
                    } else {
                        $transaction->rollBack();
                    }
                } else {
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Error al crear el usuario: ' . $e->getMessage());
            }
        }

        $roles = ArrayHelper::map(Rol::find()->orderBy(['nivel_acceso' => SORT_DESC])->all(), 'id_rol', 'nombre_rol');
        
        return $this->render('personal', [
            'user' => $user,
            'personal' => $personal,
            'roles' => $roles,
        ]);
    }
}
