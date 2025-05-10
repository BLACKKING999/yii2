<?php

namespace app\controllers;

use Yii;
use app\models\Prestamo;
use app\models\PrestamoSearch;
use app\models\Libro;
use app\models\Reserva;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class PrestamoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // Acciones que cualquier usuario autenticado puede realizar
                    [
                        'actions' => ['solicitar', 'reservar', 'mis-prestamos', 'mis-reservas', 'cancelar-reserva'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // Acciones que solo pueden realizar bibliotecarios y administradores
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'devolver', 'aprobar'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return !Yii::$app->user->isGuest && Yii::$app->user->identity->puedeAdministrarPrestamos();
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'solicitar' => ['POST'],
                    'reservar' => ['POST'],
                    'devolver' => ['POST'],
                    'cancelar-reserva' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PrestamoSearch();
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
        $model = new Prestamo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_prestamo]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_prestamo]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Prestamo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }

    /**
     * Solicita un préstamo de libro
     * @param integer $id ID del libro
     * @return mixed
     * @throws NotFoundHttpException si el libro no existe
     * @throws ForbiddenHttpException si el libro no está disponible
     */
    public function actionSolicitar($id)
    {
        $libro = Libro::findOne($id);
        if (!$libro) {
            throw new NotFoundHttpException('El libro solicitado no existe.');
        }

        if (!$libro->disponible) {
            throw new ForbiddenHttpException('Este libro no está disponible para préstamo.');
        }

        // Verificar si el usuario ya tiene el libro prestado
        $prestamoExistente = Prestamo::find()
            ->where([
                'id_libro' => $id,
                'id_usuario' => Yii::$app->user->id,
                'devuelto' => false
            ])
            ->exists();

        if ($prestamoExistente) {
            Yii::$app->session->setFlash('error', 'Ya tienes este libro prestado.');
            return $this->redirect(['catalogo/view', 'id' => $id]);
        }

        // Crear nuevo préstamo
        $prestamo = new Prestamo();
        $prestamo->id_usuario = Yii::$app->user->id;
        $prestamo->id_libro = $id;
        $prestamo->fecha_prestamo = date('Y-m-d');

        // La fecha de devolución es 14 días después (2 semanas)
        $prestamo->fecha_devolucion = date('Y-m-d', strtotime('+14 days'));
        $prestamo->devuelto = false;

        if ($prestamo->save()) {
            // Actualizar el estado del libro a no disponible
            $libro->disponible = false;
            $libro->save();

            Yii::$app->session->setFlash('success', 'Préstamo realizado con éxito. Debes devolver el libro antes del ' . Yii::$app->formatter->asDate($prestamo->fecha_devolucion));
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo procesar el préstamo. Intenta nuevamente.');
        }

        return $this->redirect(['mis-prestamos']);
    }

    /**
     * Reserva un libro que no está disponible
     * @param integer $id ID del libro
     * @return mixed
     * @throws NotFoundHttpException si el libro no existe
     */
    public function actionReservar($id)
    {
        $libro = Libro::findOne($id);
        if (!$libro) {
            throw new NotFoundHttpException('El libro solicitado no existe.');
        }

        // Verificar si el usuario ya tiene una reserva para este libro
        $reservaExistente = Reserva::find()
            ->where([
                'id_libro' => $id,
                'id_usuario' => Yii::$app->user->id,
                'activa' => true
            ])
            ->exists();

        if ($reservaExistente) {
            Yii::$app->session->setFlash('error', 'Ya tienes una reserva activa para este libro.');
            return $this->redirect(['catalogo/view', 'id' => $id]);
        }

        // Crear nueva reserva
        $reserva = new Reserva();
        $reserva->id_usuario = Yii::$app->user->id;
        $reserva->id_libro = $id;
        $reserva->fecha_reserva = date('Y-m-d');
        $reserva->activa = true;

        if ($reserva->save()) {
            Yii::$app->session->setFlash('success', 'Reserva realizada con éxito. Te notificaremos cuando el libro esté disponible.');
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo procesar la reserva. Intenta nuevamente.');
        }

        return $this->redirect(['mis-reservas']);
    }

    /**
     * Muestra los préstamos activos del usuario actual
     * @return mixed
     */
    public function actionMisPrestamos()
    {
        $query = Prestamo::find()
            ->where(['id_usuario' => Yii::$app->user->id, 'devuelto' => false])
            ->orderBy(['fecha_prestamo' => SORT_DESC]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('mis-prestamos', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra las reservas activas del usuario actual
     * @return mixed
     */
    public function actionMisReservas()
    {
        $query = Reserva::find()
            ->where(['id_usuario' => Yii::$app->user->id, 'activa' => true])
            ->orderBy(['fecha_reserva' => SORT_DESC]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('mis-reservas', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Marcar un libro como devuelto
     * @param integer $id ID del préstamo
     * @return mixed
     * @throws NotFoundHttpException si el préstamo no existe
     */
    public function actionDevolver($id)
    {
        $prestamo = $this->findModel($id);

        // Verificar que el préstamo pertenece al usuario actual
        if ($prestamo->id_usuario != Yii::$app->user->id) {
            throw new ForbiddenHttpException('No tienes permiso para realizar esta acción.');
        }

        $prestamo->devuelto = true;

        if ($prestamo->save()) {
            // Actualizar el estado del libro a disponible
            $libro = Libro::findOne($prestamo->id_libro);
            if ($libro) {
                $libro->disponible = true;
                $libro->save();

                // Notificar a los usuarios que tienen reservas para este libro
                $this->notificarReservas($prestamo->id_libro);
            }

            Yii::$app->session->setFlash('success', 'Libro devuelto con éxito.');
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo registrar la devolución. Intenta nuevamente.');
        }

        return $this->redirect(['mis-prestamos']);
    }

    /**
     * Notifica a los usuarios con reservas activas cuando un libro está disponible
     * @param integer $idLibro ID del libro
     */
    protected function notificarReservas($idLibro)
    {
        $reservas = Reserva::find()
            ->where(['id_libro' => $idLibro, 'activa' => true])
            ->orderBy(['fecha_reserva' => SORT_ASC])
            ->all();

        // Implementación de notificaciones (podría ser por email, por el sistema, etc.)
        // Aquí un ejemplo simple de marcar la primera reserva como notificada
        if (!empty($reservas)) {
            $primeraReserva = $reservas[0];
            $primeraReserva->notificado = true;
            $primeraReserva->save();
        }
    }

    /**
     * Cancela una reserva activa
     * @param integer $id ID de la reserva
     * @return mixed
     * @throws NotFoundHttpException si la reserva no existe
     * @throws ForbiddenHttpException si la reserva no pertenece al usuario actual
     */
    public function actionCancelarReserva($id)
    {
        $reserva = Reserva::findOne($id);
        if (!$reserva) {
            throw new NotFoundHttpException('La reserva solicitada no existe.');
        }

        // Verificar que la reserva pertenece al usuario actual
        if ($reserva->id_usuario != Yii::$app->user->id) {
            throw new ForbiddenHttpException('No tienes permiso para realizar esta acción.');
        }

        if ($reserva->cancelar()) {
            Yii::$app->session->setFlash('success', 'Reserva cancelada con éxito.');
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo cancelar la reserva. Intenta nuevamente.');
        }

        return $this->redirect(['mis-reservas']);
    }
}