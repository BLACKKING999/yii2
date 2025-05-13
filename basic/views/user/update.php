<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Actualizar Perfil';
$this->params['breadcrumbs'][] = ['label' => 'Mi Perfil', 'url' => ['view', 'id_usuario' => $model->id_usuario]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="user-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="user-form">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true])->hint('Dejar en blanco para mantener la contraseña actual') ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Cancelar', ['view', 'id_usuario' => $model->id_usuario], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div> 