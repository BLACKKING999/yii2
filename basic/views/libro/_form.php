<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Autor;
use app\models\Categoria;

// Determinar si es una actualización o un nuevo registro
$isNewRecord = $model->isNewRecord;

?>

<div class="libro-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'editorial')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'anio_publicacion')->textInput(['type' => 'number']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'id_autor')->dropDownList(
                ArrayHelper::map(Autor::find()->all(), 'id_autor', 'nombre_autor'),
                ['prompt' => 'Seleccione un autor']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'id_categoria')->dropDownList(
                ArrayHelper::map(Categoria::find()->all(), 'id_categoria', 'nombre_categoria'),
                ['prompt' => 'Seleccione una categoría']
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'num_paginas')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'idioma')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'ubicacion_fisica')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'disponible')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'imagenFile')->fileInput(['accept' => 'image/*']) ?>
            <?php if (!$isNewRecord && $model->imagen_portada): ?>
                <div class="mt-2">
                    <p>Imagen actual:</p>
                    <img src="<?= $model->getImagenUrl() ?>" alt="Portada del libro" style="max-width: 150px;" class="img-thumbnail" />
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