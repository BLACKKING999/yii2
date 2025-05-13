<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioPersonal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuario-personal-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <h4>Información de Usuario</h4>
            <?= $form->field($userModel, 'username')->textInput(['maxlength' => true]) ?>
            <?= $form->field($userModel, 'nombre')->textInput(['maxlength' => true]) ?>
            <?= $form->field($userModel, 'apellidos')->textInput(['maxlength' => true]) ?>
            <?= $form->field($userModel, 'email')->textInput(['maxlength' => true]) ?>
            <?= $form->field($userModel, 'password')->passwordInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <h4>Información de Personal</h4>
            <?= $form->field($model, 'departamento')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'fecha_contratacion')->widget(DatePicker::class, [
                'options' => ['placeholder' => 'Seleccione fecha...'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div> 