<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LibroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Libros';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="libro-index">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="col-md-6 text-right">
                    <?= Html::a('<i class="fa fa-plus"></i> Crear Libro', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <?php Pjax::begin(['id' => 'libros-grid']); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'layout' => "{summary}\n{items}\n<div class='text-center'>{pager}</div>",
                'summary' => '<div class="alert alert-info">Mostrando <b>{begin}-{end}</b> de <b>{totalCount}</b> libros.</div>',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id_libro',
                    'isbn',
                    [
                        'attribute' => 'titulo',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a($model->titulo, ['view', 'id_libro' => $model->id_libro], ['class' => 'text-primary']);
                        },
                    ],
                    [
                        'attribute' => 'id_autor',
                        'value' => function ($model) {
                            return $model->autor ? $model->autor->nombre_autor : 'No especificado';
                        },
                        'filter' => \yii\helpers\ArrayHelper::map(\app\models\Autor::find()->all(), 'id_autor', 'nombre_autor'),
                    ],
                    [
                        'attribute' => 'id_categoria',
                        'value' => function ($model) {
                            return $model->categoria ? $model->categoria->nombre_categoria : 'No especificada';
                        },
                        'filter' => \yii\helpers\ArrayHelper::map(\app\models\Categoria::find()->all(), 'id_categoria', 'nombre_categoria'),
                    ],
                    'editorial',
                    'anio_publicacion',
                    [
                        'attribute' => 'disponible',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->disponible 
                                ? '<span class="label label-success">Disponible</span>' 
                                : '<span class="label label-danger">No disponible</span>';
                        },
                        'filter' => [
                            1 => 'Disponible',
                            0 => 'No disponible',
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'contentOptions' => ['class' => 'text-center'],
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fa fa-eye"></i>', 
                                    ['view', 'id_libro' => $model->id_libro], 
                                    [
                                        'class' => 'btn btn-info btn-xs',
                                        'title' => 'Ver detalles',
                                        'data-toggle' => 'tooltip',
                                    ]);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<i class="fa fa-pencil"></i>', 
                                    ['update', 'id_libro' => $model->id_libro], 
                                    [
                                        'class' => 'btn btn-primary btn-xs',
                                        'title' => 'Actualizar',
                                        'data-toggle' => 'tooltip',
                                    ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<i class="fa fa-trash"></i>', 
                                    ['delete', 'id_libro' => $model->id_libro], 
                                    [
                                        'class' => 'btn btn-danger btn-xs',
                                        'title' => 'Eliminar',
                                        'data-toggle' => 'tooltip',
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
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$this->registerJs("
    $(function () {
        $('[data-toggle=\"tooltip\"]').tooltip();
    });
");
?> 