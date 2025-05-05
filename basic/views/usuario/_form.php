<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

// Determinar si es una actualización o un nuevo registro
$isNewRecord = $model->isNewRecord;

?>

<div class="usuario-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'correo')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'es_google')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'contrasena')->passwordInput(['maxlength' => true, 'placeholder' => !$isNewRecord ? 'Dejar en blanco para mantener la contraseña actual' : '']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'imagenFile')->fileInput(['accept' => 'image/*']) ?>
            <?php if (!$isNewRecord && $model->imagen_perfil): ?>
                <div class="mt-2">
                    <p>Imagen actual:</p>
                    <img src="<?= $model->getImagenUrl() ?>" alt="Imagen de perfil" style="max-width: 150px;" class="img-thumbnail" />
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div> 