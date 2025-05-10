<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mis Préstamos Activos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mis-prestamos">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="alert alert-info">
        <i class="glyphicon glyphicon-info-sign"></i> Aquí puedes ver todos tus préstamos activos. Recuerda devolver los libros antes de la fecha límite para evitar sanciones.
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
                    'attribute' => 'fecha_prestamo',
                    'format' => 'date',
                    'label' => 'Fecha de préstamo',
                ],
                [
                    'attribute' => 'fecha_devolucion',
                    'format' => 'date',
                    'label' => 'Fecha de devolución',
                    'contentOptions' => function ($model) {
                        $hoy = new DateTime();
                        $fechaDev = new DateTime($model->fecha_devolucion);
                        $diff = $hoy->diff($fechaDev);
                        
                        if ($hoy > $fechaDev) {
                            return ['class' => 'danger'];
                        } elseif ($diff->days <= 3) {
                            return ['class' => 'warning'];
                        } else {
                            return ['class' => 'success'];
                        }
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{devolver}',
                    'buttons' => [
                        'devolver' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-ok"></span> Devolver', 
                                ['devolver', 'id' => $model->id_prestamo], 
                                [
                                    'class' => 'btn btn-success btn-sm',
                                    'data-method' => 'post',
                                    'data-confirm' => '¿Estás seguro de que deseas marcar este libro como devuelto?',
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
            <p>No tienes ningún préstamo activo en este momento.</p>
            <p><?= Html::a('Navegar el catálogo', ['catalogo/index'], ['class' => 'btn btn-primary']) ?></p>
        </div>
    <?php endif; ?>
</div>
