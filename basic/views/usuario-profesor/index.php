<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuarioProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Profesores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-profesor-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Registrar Profesor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id_profesor',
                'label' => 'ID',
            ],
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
            [
                'attribute' => 'email',
                'value' => 'user.email',
                'label' => 'Correo',
            ],
            [
                'attribute' => 'especialidad',
                'label' => 'Especialidad',
            ],
            [
                'attribute' => 'departamento',
                'label' => 'Departamento',
            ],
            [
                'attribute' => 'oficina',
                'label' => 'Oficina',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => 'Ver detalles',
                            'class' => 'btn btn-sm btn-info',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fas fa-edit"></i>', $url, [
                            'title' => 'Actualizar',
                            'class' => 'btn btn-sm btn-primary',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                            'title' => 'Eliminar',
                            'class' => 'btn btn-sm btn-danger',
                            'data' => [
                                'confirm' => '¿Está seguro que desea eliminar este profesor?',
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
