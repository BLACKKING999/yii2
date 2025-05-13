<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioPersonal */

$this->title = $model->user->nombreCompleto;
$this->params['breadcrumbs'][] = ['label' => 'Gestión de Personal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-personal-view">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <p>
                <?= Html::a('Actualizar', ['update', 'id' => $model->id_personal], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Eliminar', ['delete', 'id' => $model->id_personal], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => '¿Está seguro que desea eliminar este personal?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'username',
                        'value' => $model->user->username,
                        'label' => 'Usuario',
                    ],
                    [
                        'attribute' => 'nombre',
                        'value' => $model->user->nombre,
                        'label' => 'Nombre',
                    ],
                    [
                        'attribute' => 'apellidos',
                        'value' => $model->user->apellidos,
                        'label' => 'Apellidos',
                    ],
                    [
                        'attribute' => 'email',
                        'value' => $model->user->email,
                        'label' => 'Correo Electrónico',
                    ],
                    'departamento',
                    'cargo',
                    'fecha_contratacion:date',
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
    </div>
</div> 