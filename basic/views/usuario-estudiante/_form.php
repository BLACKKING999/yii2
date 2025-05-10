<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioEstudiante */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuario-estudiante-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'carnet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'carrera')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'semestre')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
