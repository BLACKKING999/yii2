<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Biblioteca Virtual - Libros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="libro-index">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('<i class="fas fa-plus"></i> Agregar Nuevo Libro', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'imagen_portada',
                    'format' => 'html',
                    'label' => 'Portada',
                    'value' => function($model) {
                        if ($model->imagen_portada) {
                            return Html::img($model->getImagenUrl(), ['style' => 'width: 70px; height: 100px; object-fit: cover;', 'class' => 'img-thumbnail']);
                        } else {
                            return Html::img(Url::to('@web/uploads/default.png'), ['style' => 'width: 70px; height: 100px; object-fit: cover;', 'class' => 'img-thumbnail']);
                        }
                    },
                ],
                'titulo',
                [
                    'attribute' => 'id_autor',
                    'value' => 'autor.nombre_autor',
                    'label' => 'Autor'
                ],
                [
                    'attribute' => 'id_categoria',
                    'value' => 'categoria.nombre_categoria',
                    'label' => 'Categoría'
                ],
                'anio_publicacion',
                [
                    'attribute' => 'disponible',
                    'value' => function ($model) {
                        return $model->disponible ? 'Sí' : 'No';
                    }
                ],
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
                                    'confirm' => '¿Está seguro de eliminar este libro?',
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