<?php

use app\models\User;
use app\models\Rol;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    <div class="help-block">Dejar en blanco para mantener la contraseña actual</div>

    <?php if ($model->scenario === User::SCENARIO_UPDATE): ?>
        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'id_rol')->dropDownList(
            ArrayHelper::map(Rol::find()->all(), 'id_rol', 'nombre_rol'),
            ['prompt' => 'Seleccione un rol']
        ) ?>
    <?php endif; ?>

    <?= $form->field($model, 'imagenFile')->fileInput() ?>
    <?php if ($model->imagen_perfil): ?>
        <div class="form-group">
            <label>Imagen actual:</label><br>
            <img src="<?= $model->imagenUrl ?>" alt="Imagen de perfil" style="max-width: 200px;">
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?> 