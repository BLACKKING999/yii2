<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Administración de Roles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rol-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Nuevo Rol', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_rol',
            'nombre_rol',
            'descripcion',
            'nivel_acceso',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'label' => 'Creado en',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {permisos}',
                'buttons' => [
                    'permisos' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-lock"></span>', 
                            ['asignar-permisos', 'id' => $model->id_rol], 
                            ['title' => 'Asignar Permisos']
                        );
                    },
                ],
            ],
        ],
    ]); ?>


</div>
