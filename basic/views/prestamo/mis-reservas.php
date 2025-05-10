<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mis Reservas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mis-reservas">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="alert alert-info">
        <i class="glyphicon glyphicon-info-sign"></i> Aquí puedes ver todas tus reservas activas. Te notificaremos cuando el libro esté disponible para que puedas solicitarlo.
    </div>

    <?php if ($dataProvider->totalCount > 0): ?>
        <?php Pjax::begin(); ?>
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'libro.titulo',
                    'label' => 'Libro',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a(
                            Html::encode($model->libro->titulo),
                            ['catalogo/view', 'id' => $model->id_libro]
                        );
                    },
                ],
                [
                    'attribute' => 'libro.autor.nombre_autor',
                    'label' => 'Autor',
                    'value' => function ($model) {
                        return $model->libro && $model->libro->autor 
                            ? $model->libro->autor->getNombreCompleto() 
                            : '<span class="not-set">(no definido)</span>';
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'fecha_reserva',
                    'format' => 'date',
                    'label' => 'Fecha de reserva',
                ],
                [
                    'attribute' => 'notificado',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->notificado 
                            ? '<span class="label label-success">Notificado</span>' 
                            : '<span class="label label-warning">Pendiente</span>';
                    },
                    'label' => 'Estado',
                ],
                [
                    'attribute' => 'libro.disponible',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->libro && $model->libro->disponible) {
                            return Html::a(
                                '<span class="label label-success">Disponible</span>', 
                                ['catalogo/view', 'id' => $model->id_libro], 
                                ['class' => 'btn-link']
                            );
                        } else {
                            return '<span class="label label-danger">No disponible</span>';
                        }
                    },
                    'label' => 'Disponibilidad',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{cancelar}',
                    'buttons' => [
                        'cancelar' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-remove"></span> Cancelar', 
                                ['cancelar-reserva', 'id' => $model->id_reserva], 
                                [
                                    'class' => 'btn btn-danger btn-sm',
                                    'data-method' => 'post',
                                    'data-confirm' => '¿Estás seguro de que deseas cancelar esta reserva?',
                                ]
                            );
                        },
                    ],
                ],
            ],
        ]); ?>
        
        <?php Pjax::end(); ?>
    <?php else: ?>
        <div class="alert alert-warning">
            <p>No tienes ninguna reserva activa en este momento.</p>
            <p><?= Html::a('Navegar el catálogo', ['catalogo/index'], ['class' => 'btn btn-primary']) ?></p>
        </div>
    <?php endif; ?>
</div>
