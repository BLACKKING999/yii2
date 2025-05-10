<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gestión de Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Usuario', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'role',
                'value' => function ($model) {
                    return $model->role === $model::ROLE_ADMIN ? 'Administrador' : 'Usuario';
                },
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    $statuses = [
                        $model::STATUS_ACTIVE => 'Activo',
                        $model::STATUS_INACTIVE => 'Inactivo',
                        $model::STATUS_DELETED => 'Eliminado',
                    ];
                    return $statuses[$model->status] ?? 'Desconocido';
                },
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('d/m/Y H:i', $model->created_at);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {reset}',
                'buttons' => [
                    'reset' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-lock"></span>', 
                            ['reset-password', 'id' => $model->id], 
                            [
                                'title' => 'Restablecer contraseña',
                                'data-confirm' => '¿Estás seguro de que quieres restablecer la contraseña de este usuario?',
                                'data-method' => 'post',
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
