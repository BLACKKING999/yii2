<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioPersonal */

$this->title = 'Actualizar Personal: ' . $model->user->nombreCompleto;
$this->params['breadcrumbs'][] = ['label' => 'Gestión de Personal', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user->nombreCompleto, 'url' => ['view', 'id' => $model->id_personal]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="usuario-personal-update">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <?= $this->render('_form', [
                'model' => $model,
                'userModel' => $userModel,
            ]) ?>
        </div>
    </div>
</div> 