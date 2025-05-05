<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\LibroSearch;

?>

<div class="libro-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'titulo')->textInput(['placeholder' => 'Buscar por título...']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'id_autor')->dropDownList(
                LibroSearch::getAutoresList(),
                ['prompt' => 'Seleccione un autor...']
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'id_categoria')->dropDownList(
                LibroSearch::getCategoriasList(),
                ['prompt' => 'Seleccione una categoría...']
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'anio_publicacion')->dropDownList(
                LibroSearch::getAniosList(),
                ['prompt' => 'Seleccione un año...']
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'disponible')->dropDownList(
                [1 => 'Sí', 0 => 'No'],
                ['prompt' => 'Seleccione disponibilidad...']
            ) ?>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-top: 24px;">
                <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Limpiar', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div> 