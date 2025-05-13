<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Mi Perfil';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Actualizar Perfil', ['update', 'id_usuario' => $model->id_usuario], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_usuario',
            'username',
            'nombre',
            'apellidos',
            'email:email',
            [
                'attribute' => 'id_rol',
                'value' => function($model) {
                    return $model->rol ? $model->rol->nombre : 'No asignado';
                },
            ],
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status == 10 ? 'Activo' : 'Inactivo';
                },
            ],
            [
                'attribute' => 'fecha_registro',
                'format' => 'datetime',
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
            ],
        ],
    ]) ?>

</div> 