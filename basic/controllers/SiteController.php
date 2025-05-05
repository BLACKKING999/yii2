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
use app\models\Usuario;
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
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
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
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
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
            
            // Buscar usuario por Google ID o correo electrónico
            $usuario = Usuario::findOne(['google_id' => $userData['id']]);
            
            if (!$usuario) {
                // Buscar por correo
                $usuario = Usuario::findOne(['correo' => $userData['email']]);
                
                if (!$usuario) {
                    // Crear nuevo usuario
                    $usuario = new Usuario();
                    $usuario->nombre = $userData['name'];
                    $usuario->correo = $userData['email'];
                    $usuario->google_id = $userData['id'];
                    $usuario->es_google = true;
                    $usuario->contrasena = Yii::$app->security->generateRandomString(12); // Contraseña aleatoria
                    
                    if (!$usuario->save()) {
                        Yii::$app->session->setFlash('error', 'Error al crear usuario con Google');
                        return $this->redirect(['site/login']);
                    }
                } else {
                    // Actualizar usuario existente con datos de Google
                    $usuario->google_id = $userData['id'];
                    $usuario->es_google = true;
                    $usuario->save();
                }
            }
            
            // Iniciar sesión
            Yii::$app->user->login($usuario);
            
            // Redireccionar a una página específica en lugar de usar goHome()
            return $this->redirect(['/site/index']);
        }
        
        Yii::$app->session->setFlash('error', 'Error de autenticación con Google');
        return $this->redirect(['site/login']);
    }
}
