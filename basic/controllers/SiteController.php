<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\AutenticacionExterna;
use app\models\Rol;
use app\components\FirebaseService;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'dashboard'],
                'rules' => [
                    [
                        'actions' => ['logout', 'dashboard'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    return $this->redirect(['site/login']);
                },
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Si el usuario ha iniciado sesión, mostrar el dashboard
        if (!Yii::$app->user->isGuest) {
            return $this->render('dashboard');
        }
        
        // Si no ha iniciado sesión, mostrar la página principal
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                $nombreUsuario = Yii::$app->user->identity->nombre ?: Yii::$app->user->identity->username;
                Yii::$app->session->setFlash('success', 'Bienvenido, ' . $nombreUsuario);
                return $this->goBack();
            } else {
                // El mensaje de error ya se muestra en el formulario
            }
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    /**
     * Muestra la página de inicio de sesión con Google
     * @return string Página de inicio de sesión con Google
     */
    public function actionAuthGoogle()
    {
        $firebaseService = new FirebaseService();
        return $this->render('auth-google', [
            'authUrl' => $firebaseService->getAuthUrl()
        ]);
    }
    
    /**
     * Redirecciona directamente a Google para iniciar sesión
     * @return Response Redirección a Google
     */
    public function actionAuthGoogleRedirect()
    {
        $firebaseService = new FirebaseService();
        return $this->redirect($firebaseService->getAuthUrl());
    }
    
    /**
     * Callback para procesar la respuesta de Google después de la autenticación.
     *
     * @return Response
     */
    public function actionAuthCallback()
    {
        // Verificar el código de autorización
        if (isset($_GET['code'])) {
            $firebaseService = new FirebaseService();
            $tokenData = $firebaseService->getAccessToken($_GET['code']);
            
            if (!$tokenData || !isset($tokenData['access_token'])) {
                Yii::$app->session->setFlash('error', 'Error al obtener token de acceso');
                return $this->redirect(['site/login']);
            }
            
            $userData = $firebaseService->getUserInfo($tokenData['access_token']);
            
            if (!$userData || !isset($userData['id']) || !isset($userData['email'])) {
                Yii::$app->session->setFlash('error', 'Error al obtener datos del usuario');
                return $this->redirect(['site/login']);
            }
            
            // Usar el modelo de login externo para gestionar el proceso
            $loginForm = new LoginForm();
            $result = $loginForm->loginExternal(
                AutenticacionExterna::PROVEEDOR_GOOGLE, 
                $userData['id'], 
                [
                    'email' => $userData['email'],
                    'name' => $userData['name']
                ],
                $tokenData['access_token']
            );
            
            if ($result instanceof User) {
                // Si es una instancia de User, es un usuario nuevo creado
                Yii::$app->usuario->login($result, 3600*24*30);
                Yii::$app->session->setFlash('success', 'Bienvenido ' . $result->nombre . '. Tu cuenta ha sido creada correctamente.');
            } elseif ($result) {
                // Login exitoso
                $nombreUsuario = Yii::$app->user->identity->nombre ?: Yii::$app->user->identity->username;
                Yii::$app->session->setFlash('success', 'Bienvenido, ' . $nombreUsuario);
            } else {
                // Error en login
                Yii::$app->session->setFlash('error', 'Error de autenticación con Google');
                return $this->redirect(['site/login']);
            }
            
            // Redireccionar a una página específica en lugar de usar goHome()
            return $this->redirect(['/site/index']);
        }
        
        Yii::$app->session->setFlash('error', 'Error de autenticación con Google');
        return $this->redirect(['site/login']);
    }
}
