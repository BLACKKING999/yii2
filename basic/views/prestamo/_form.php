<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Usuario;
use app\models\Libro;

?>

<div class="prestamo-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'id_usuario')->dropDownList(
                ArrayHelper::map(Usuario::find()->all(), 'id_usuario', 'nombre'),
                ['prompt' => 'Seleccione un usuario']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'id_libro')->dropDownList(
                ArrayHelper::map(Libro::find()->where(['disponible' => true])->all(), 'id_libro', 'titulo'),
                ['prompt' => 'Seleccione un libro']
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'fecha_prestamo')->input('date') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'fecha_devolucion')->input('date') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'devuelto')->checkbox() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div> 