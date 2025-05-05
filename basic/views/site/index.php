<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'Biblioteca Virtual';
?>

<div class="site-index">
    <div class="jumbotron text-center bg-light">
        <h1 class="display-4">Biblioteca Virtual</h1>
        <p class="lead">Sistema de gestión de libros, préstamos y usuarios</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-book fa-3x mb-3 text-primary"></i>
                        <h3 class="card-title">Libros</h3>
                        <p class="card-text">Gestiona el catálogo de libros, autores y categorías.</p>
                        <div class="mt-3">
                            <?= Html::a('Ver Libros', ['/libro/index'], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Nuevo Libro', ['/libro/create'], ['class' => 'btn btn-outline-primary']) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-3 text-success"></i>
                        <h3 class="card-title">Usuarios</h3>
                        <p class="card-text">Administra los usuarios registrados en el sistema.</p>
                        <div class="mt-3">
                            <?= Html::a('Ver Usuarios', ['/usuario/index'], ['class' => 'btn btn-success']) ?>
                            <?= Html::a('Nuevo Usuario', ['/usuario/create'], ['class' => 'btn btn-outline-success']) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-exchange-alt fa-3x mb-3 text-info"></i>
                        <h3 class="card-title">Préstamos</h3>
                        <p class="card-text">Controla los préstamos y devoluciones de libros.</p>
                        <div class="mt-3">
                            <?= Html::a('Ver Préstamos', ['/prestamo/index'], ['class' => 'btn btn-info']) ?>
                            <?= Html::a('Nuevo Préstamo', ['/prestamo/create'], ['class' => 'btn btn-outline-info']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-user-edit fa-3x mb-3 text-warning"></i>
                        <h3 class="card-title">Autores</h3>
                        <p class="card-text">Gestiona la información de los autores.</p>
                        <div class="mt-3">
                            <?= Html::a('Ver Autores', ['/autor/index'], ['class' => 'btn btn-warning']) ?>
                            <?= Html::a('Nuevo Autor', ['/autor/create'], ['class' => 'btn btn-outline-warning']) ?>
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
                            <?= Html::a('Nueva Categoría', ['/categoria/create'], ['class' => 'btn btn-outline-danger']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                                <p class="h2 text-success"><?= \app\models\Usuario::find()->count() ?></p>
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
    </div>
</div>

<?php
// Registrar Font Awesome para los iconos
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
?>
