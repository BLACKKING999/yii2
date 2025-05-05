<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="usuario-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'nombre')->textInput(['placeholder' => 'Buscar por nombre...']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'correo')->textInput(['placeholder' => 'Buscar por correo...']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'fecha_registro')->input('date') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Limpiar', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div> 