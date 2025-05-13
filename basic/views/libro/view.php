<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Libro */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Libros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="libro-view">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="col-md-6 text-right">
                    <?= Html::a('<i class="fa fa-pencil"></i> Actualizar', ['update', 'id_libro' => $model->id_libro], ['class' => 'btn btn-primary btn-sm']) ?>
                    <?= Html::a('<i class="fa fa-trash"></i> Eliminar', ['delete', 'id_libro' => $model->id_libro], [
                        'class' => 'btn btn-danger btn-sm',
                        'data' => [
                            'confirm' => '¿Está seguro de eliminar este libro?',
                            'method' => 'post',
                        ],
                    ]) ?>
                    <?= Html::a('<i class="fa fa-arrow-left"></i> Volver', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="thumbnail">
                        <?php if ($model->imagen_portada): ?>
                            <?= Html::img($model->getImagenUrl(), ['class' => 'img-responsive', 'alt' => $model->titulo]) ?>
                        <?php else: ?>
                            <?= Html::img('@web/images/no-image.png', ['class' => 'img-responsive', 'alt' => 'Sin imagen']) ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id_libro',
                            'isbn',
                            [
                                'attribute' => 'titulo',
                                'label' => 'Título',
                                'contentOptions' => ['class' => 'text-primary'],
                            ],
                            [
                                'attribute' => 'id_autor',
                                'label' => 'Autor',
                                'value' => $model->autor ? $model->autor->nombre_autor : 'No especificado',
                                'contentOptions' => ['class' => 'text-muted'],
                            ],
                            [
                                'attribute' => 'id_categoria',
                                'label' => 'Categoría',
                                'value' => $model->categoria ? $model->categoria->nombre_categoria : 'No especificada',
                                'contentOptions' => ['class' => 'text-muted'],
                            ],
                            'editorial',
                            'anio_publicacion',
                            'num_paginas',
                            'idioma',
                            'ubicacion_fisica',
                            [
                                'attribute' => 'disponible',
                                'format' => 'raw',
                                'value' => $model->disponible 
                                    ? '<span class="label label-success">Disponible</span>' 
                                    : '<span class="label label-danger">No disponible</span>',
                            ],
                            [
                                'attribute' => 'descripcion',
                                'format' => 'ntext',
                                'contentOptions' => ['class' => 'text-muted'],
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => 'datetime',
                                'label' => 'Fecha de Creación',
                            ],
                            [
                                'attribute' => 'updated_at',
                                'format' => 'datetime',
                                'label' => 'Última Actualización',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
