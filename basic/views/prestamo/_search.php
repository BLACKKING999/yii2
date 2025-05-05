<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\PrestamoSearch;

?>

<div class="prestamo-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'id_usuario')->dropDownList(
                PrestamoSearch::getUsuariosList(),
                ['prompt' => 'Seleccione un usuario...']
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'id_libro')->dropDownList(
                PrestamoSearch::getLibrosList(),
                ['prompt' => 'Seleccione un libro...']
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'devuelto')->dropDownList(
                [1 => 'Sí', 0 => 'No'],
                ['prompt' => 'Seleccione estado...']
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'fecha_prestamo')->input('date') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'fecha_devolucion')->input('date') ?>
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