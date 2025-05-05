<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="autor-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'nombre_autor')->textInput(['placeholder' => 'Buscar por nombre...']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'nacionalidad')->textInput(['placeholder' => 'Buscar por nacionalidad...']) ?>
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