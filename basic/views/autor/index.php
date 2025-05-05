<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Autores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="autor-index">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('<i class="fas fa-plus"></i> Agregar Nuevo Autor', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'imagen_autor',
                    'format' => 'html',
                    'label' => 'Imagen',
                    'value' => function($model) {
                        if ($model->imagen_autor) {
                            return Html::img($model->getImagenUrl(), ['style' => 'width: 60px; height: 60px; object-fit: cover;', 'class' => 'img-thumbnail rounded-circle']);
                        } else {
                            return Html::img(Url::to('@web/uploads/default.png'), ['style' => 'width: 60px; height: 60px; object-fit: cover;', 'class' => 'img-thumbnail rounded-circle']);
                        }
                    },
                ],
                'nombre_autor',
                'nacionalidad',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<i class="fas fa-eye"></i>', $url, [
                                'title' => 'Ver',
                                'class' => 'btn btn-info btn-sm'
                            ]);
                        },
                        'update' => function ($url, $model) {
                            return Html::a('<i class="fas fa-edit"></i>', $url, [
                                'title' => 'Editar',
                                'class' => 'btn btn-primary btn-sm'
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<i class="fas fa-trash-alt"></i>', $url, [
                                'title' => 'Eliminar',
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => '¿Está seguro de eliminar este autor?',
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