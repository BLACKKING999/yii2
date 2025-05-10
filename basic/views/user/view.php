<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->identity->isAdmin() || Yii::$app->user->id == $model->id): ?>
            <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        
        <?php if (Yii::$app->user->identity->isAdmin()): ?>
            <?= Html::a('<i class="glyphicon glyphicon-trash"></i> Eliminar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => '¿Estás seguro de que quieres eliminar este usuario?',
                    'method' => 'post',
                ],
            ]) ?>
            
            <?= Html::a('<i class="glyphicon glyphicon-lock"></i> Restablecer Contraseña', ['reset-password', 'id' => $model->id], [
                'class' => 'btn btn-warning',
                'data' => [
                    'confirm' => '¿Estás seguro de que quieres restablecer la contraseña de este usuario?',
                    'method' => 'post',
                ],
            ]) ?>
            
            <?= Html::a('<i class="glyphicon glyphicon-link"></i> Vincular con Usuario Biblioteca', ['vincular-usuario', 'id' => $model->id], [
                'class' => 'btn btn-info',
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'id_rol',
                'label' => 'Rol',
                'value' => function ($model) {
                    return $model->rol ? $model->rol->nombre_rol : 'Sin rol';
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
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return date('d/m/Y H:i', $model->updated_at);
                },
            ],
            // Usuario normalizado info
            [
                'label' => 'Tipo de Usuario',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->esEstudiante()) {
                        $estudiante = $model->getUsuarioEstudiante()->one();
                        if ($estudiante) {
                            return Html::a(
                                '<i class="glyphicon glyphicon-education"></i> Estudiante: ' . 
                                Html::encode($estudiante->carrera . ' (Semestre ' . $estudiante->semestre . ')'),
                                '#',
                                ['class' => 'label label-info']
                            );
                        }
                    } else if ($model->esProfesor()) {
                        $profesor = $model->getUsuarioProfesor()->one();
                        if ($profesor) {
                            return Html::a(
                                '<i class="glyphicon glyphicon-briefcase"></i> Profesor: ' . 
                                Html::encode($profesor->departamento),
                                '#',
                                ['class' => 'label label-success']
                            );
                        }
                    }
                    return '<span class="label label-default">Usuario general</span>';
                },
            ],
        ],
    ]) ?>

</div>
