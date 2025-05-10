<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioProfesor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuario-profesor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'especialidad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'departamento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'oficina')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
