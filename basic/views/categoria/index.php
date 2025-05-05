<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Categorías';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categoria-index">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('<i class="fas fa-plus"></i> Agregar Nueva Categoría', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'nombre_categoria',
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
                                    'confirm' => '¿Está seguro de eliminar esta categoría?',
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