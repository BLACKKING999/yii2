<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-index">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Agregar Nuevo Usuario', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'imagen_perfil',
                    'format' => 'html',
                    'label' => 'Imagen',
                    'value' => function($model) {
                        if ($model->imagen_perfil) {
                            return Html::img($model->getImagenUrl(), ['style' => 'width: 50px; height: 50px; object-fit: cover;', 'class' => 'img-thumbnail']);
                        } else {
                            return Html::img(Url::to('@web/uploads/default.png'), ['style' => 'width: 50px; height: 50px; object-fit: cover;', 'class' => 'img-thumbnail']);
                        }
                    },
                ],
                'nombre',
                'correo',
                [
                    'attribute' => 'es_google',
                    'format' => 'boolean',
                    'filter' => [0 => 'No', 1 => 'Sí'],
                ],
                [
                    'attribute' => 'fecha_registro',
                    'format' => ['date', 'php:d/m/Y'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => 'Ver',
                                'class' => 'btn btn-info btn-sm'
                            ]);
                        },
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => 'Editar',
                                'class' => 'btn btn-primary btn-sm'
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => 'Eliminar',
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => '¿Está seguro de eliminar este usuario?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
</div> 