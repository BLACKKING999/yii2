<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuarioPersonalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gestión de Personal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-personal-index">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <p>
                <?= Html::a('Registrar Nuevo Personal', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'username',
                        'value' => 'user.username',
                        'label' => 'Usuario',
                    ],
                    [
                        'attribute' => 'nombre',
                        'value' => 'user.nombre',
                        'label' => 'Nombre',
                    ],
                    [
                        'attribute' => 'apellidos',
                        'value' => 'user.apellidos',
                        'label' => 'Apellidos',
                    ],
                    'departamento',
                    'cargo',
                    'fecha_contratacion:date',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => 'Ver',
                                    'class' => 'btn btn-info btn-xs',
                                ]);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                    'title' => 'Actualizar',
                                    'class' => 'btn btn-primary btn-xs',
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                    'title' => 'Eliminar',
                                    'class' => 'btn btn-danger btn-xs',
                                    'data' => [
                                        'confirm' => '¿Está seguro que desea eliminar este personal?',
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