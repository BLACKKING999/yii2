<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'Biblioteca Virtual';
?>

<div class="site-index">
    <div class="jumbotron text-center bg-light p-5 mb-4">
        <h1 class="display-4">Biblioteca Virtual</h1>
        <p class="lead">Sistema de gestión de libros, préstamos y usuarios</p>
        
        <?php if (Yii::$app->user->isGuest): ?>
            <p class="mt-4">
                <?= Html::a('Iniciar Sesión', ['/site/login'], ['class' => 'btn btn-primary btn-lg']) ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="container">
        <!-- Catálogo - Visible para todos -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-search fa-3x mb-3 text-primary"></i>
                        <h2 class="card-title">Explora Nuestro Catálogo</h2>
                        <p class="card-text">Descubre nuestra colección de libros disponibles para préstamo.</p>
                        <div class="mt-3">
                            <?= Html::a('Ver Catálogo', ['/catalogo/index'], ['class' => 'btn btn-primary btn-lg']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!Yii::$app->user->isGuest): ?>
        <div class="row">
            <?php if (Yii::$app->usuario->identity->puedeAdministrarLibros()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-book fa-3x mb-3 text-primary"></i>
                        <h3 class="card-title">Libros</h3>
                        <p class="card-text">Gestiona el catálogo de libros, autores y categorías.</p>
                        <div class="mt-3">
                            <?= Html::a('Gestionar Libros', ['/libro/index'], ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (Yii::$app->usuario->identity->puedeAdministrarUsuarios()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-3 text-success"></i>
                        <h3 class="card-title">Usuarios</h3>
                        <p class="card-text">Administra los usuarios registrados en el sistema.</p>
                        <div class="mt-3">
                            <?= Html::a('Gestionar Usuarios', ['/user/index'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-book-reader fa-3x mb-3 text-info"></i>
                        <h3 class="card-title">Mis Préstamos</h3>
                        <p class="card-text">Consulta tus préstamos activos y devoluciones pendientes.</p>
                        <div class="mt-3">
                            <?= Html::a('Mis Préstamos', ['/prestamo/mis-prestamos'], ['class' => 'btn btn-info']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (Yii::$app->usuario->identity->puedeAdministrarLibros()): ?>
        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-user-edit fa-3x mb-3 text-warning"></i>
                        <h3 class="card-title">Autores</h3>
                        <p class="card-text">Gestiona la información de los autores.</p>
                        <div class="mt-3">
                            <?= Html::a('Ver Autores', ['/autor/index'], ['class' => 'btn btn-warning']) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-tags fa-3x mb-3 text-danger"></i>
                        <h3 class="card-title">Categorías</h3>
                        <p class="card-text">Organiza los libros por categorías.</p>
                        <div class="mt-3">
                            <?= Html::a('Ver Categorías', ['/categoria/index'], ['class' => 'btn btn-danger']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (Yii::$app->usuario->identity->isAdmin()): ?>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Estadísticas Rápidas</h4>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h5>Total Libros</h5>
                                <p class="h2 text-primary"><?= \app\models\Libro::find()->count() ?></p>
                            </div>
                            <div class="col-md-3">
                                <h5>Usuarios Activos</h5>
                                <p class="h2 text-success"><?= \app\models\User::find()->count() ?></p>
                            </div>
                            <div class="col-md-3">
                                <h5>Préstamos Activos</h5>
                                <p class="h2 text-info"><?= \app\models\Prestamo::find()->where(['devuelto' => 0])->count() ?></p>
                            </div>
                            <div class="col-md-3">
                                <h5>Categorías</h5>
                                <p class="h2 text-warning"><?= \app\models\Categoria::find()->count() ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php
// Registrar Font Awesome para los iconos
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
?>
