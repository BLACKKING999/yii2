<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Libro */
/* @var $relacionados app\models\Libro[] */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Catálogo', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="libro-view">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Portada</h3>
                </div>
                <div class="panel-body text-center">
                    <?php if ($model->imagen_portada): ?>
                        <img src="<?= Yii::getAlias('@web/uploads/libros/') . $model->imagen_portada ?>" alt="<?= Html::encode($model->titulo) ?>" class="img-responsive" style="max-height: 400px; margin: 0 auto;">
                    <?php else: ?>
                        <img src="<?= Yii::getAlias('@web/img/no-book-cover.png') ?>" alt="Portada no disponible" class="img-responsive" style="max-height: 400px; margin: 0 auto;">
                    <?php endif; ?>
                    
                    <?php if ($model->disponible): ?>
                        <div class="alert alert-success mt-3">
                            <i class="glyphicon glyphicon-ok-circle"></i> Este libro está disponible para préstamo
                        </div>
                        
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <?= Html::a('Solicitar préstamo', ['prestamo/solicitar', 'id' => $model->id_libro], [
                                'class' => 'btn btn-success btn-lg btn-block',
                                'data' => [
                                    'confirm' => '¿Estás seguro de que deseas solicitar este libro?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php else: ?>
                            <?= Html::a('Iniciar sesión para solicitar', ['site/login'], [
                                'class' => 'btn btn-primary btn-block',
                            ]) ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-danger mt-3">
                            <i class="glyphicon glyphicon-remove-circle"></i> Este libro no está disponible actualmente
                        </div>
                        
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <?= Html::a('Reservar para cuando esté disponible', ['prestamo/reservar', 'id' => $model->id_libro], [
                                'class' => 'btn btn-warning btn-block',
                                'data' => [
                                    'confirm' => '¿Estás seguro de que deseas reservar este libro?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Detalles del libro</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'isbn',
                            [
                                'attribute' => 'autor.nombre_autor',
                                'label' => 'Autor',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->autor) {
                                        return Html::a(
                                            Html::encode($model->autor->getNombreCompleto()),
                                            ['catalogo/autor', 'id' => $model->id_autor]
                                        );
                                    }
                                    return '<span class="not-set">(no definido)</span>';
                                },
                            ],
                            [
                                'attribute' => 'categoria.nombre_categoria',
                                'label' => 'Categoría',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->categoria) {
                                        return Html::a(
                                            Html::encode($model->categoria->nombre_categoria),
                                            ['catalogo/categoria', 'id' => $model->id_categoria]
                                        );
                                    }
                                    return '<span class="not-set">(no definido)</span>';
                                },
                            ],
                            'editorial',
                            'anio_publicacion:integer',
                            'num_paginas:integer',
                            'idioma',
                            [
                                'attribute' => 'disponible',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $model->disponible 
                                        ? '<span class="label label-success">Disponible</span>' 
                                        : '<span class="label label-danger">No disponible</span>';
                                },
                            ],
                            'ubicacion_fisica',
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                </div>
            </div>
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Descripción</h3>
                </div>
                <div class="panel-body">
                    <?php if ($model->descripcion): ?>
                        <p class="text-justify"><?= nl2br(Html::encode($model->descripcion)) ?></p>
                    <?php else: ?>
                        <p class="text-muted">No hay descripción disponible para este libro.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (!empty($relacionados)): ?>
    <div class="relacionados">
        <h2>Libros relacionados</h2>
        <div class="row">
            <?php foreach ($relacionados as $libro): ?>
                <div class="col-md-3">
                    <div class="panel panel-default libro-card" style="height: 400px;">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?= Html::encode($libro->titulo) ?></h3>
                        </div>
                        <div class="panel-body">
                            <div class="libro-imagen">
                                <?php if ($libro->imagen_portada): ?>
                                    <img src="<?= Yii::getAlias('@web/uploads/libros/') . $libro->imagen_portada ?>" alt="<?= Html::encode($libro->titulo) ?>" style="max-height: 150px; max-width: 100%;">
                                <?php else: ?>
                                    <img src="<?= Yii::getAlias('@web/img/no-book-cover.png') ?>" alt="Portada no disponible" style="max-height: 150px; max-width: 100%;">
                                <?php endif; ?>
                            </div>
                            
                            <div class="libro-info mt-2">
                                <?php if ($libro->autor): ?>
                                    <p><small><strong>Autor:</strong> <?= Html::encode($libro->autor->getNombreCompleto()) ?></small></p>
                                <?php endif; ?>
                                <p><small><strong>Año:</strong> <?= Html::encode($libro->anio_publicacion) ?></small></p>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <?= Html::a('Ver detalles', ['catalogo/view', 'id' => $libro->id_libro], ['class' => 'btn btn-primary btn-block']) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
