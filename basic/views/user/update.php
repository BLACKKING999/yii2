<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Actualizar Usuario: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <p><i class="glyphicon glyphicon-info-sign"></i> Si no desea cambiar la contraseña, deje el campo en blanco.</p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
