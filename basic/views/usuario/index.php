<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <p>
                <?= Html::a('Crear Usuario', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'username',
                    'nombre',
                    'apellidos',
                    'email',
                    [
                        'attribute' => 'id_rol',
                        'value' => 'rol.nombre_rol',
                        'filter' => \yii\helpers\ArrayHelper::map(\app\models\Rol::find()->all(), 'id_rol', 'nombre_rol'),
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return $model->status == 10 ? 'Activo' : 'Inactivo';
                        },
                        'filter' => [
                            10 => 'Activo',
                            0 => 'Inactivo',
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
                                    ['view', 'id_usuario' => $model->id_usuario], 
                                    ['title' => 'Ver']);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
                                    ['update', 'id_usuario' => $model->id_usuario], 
                                    ['title' => 'Actualizar']);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                                    ['delete', 'id_usuario' => $model->id_usuario], 
                                    [
                                        'title' => 'Eliminar',
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
            <?php Pjax::end(); ?>
        </div>
    </div>
</div> 