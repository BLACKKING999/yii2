<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Usuario $model */

$this->title = 'Crear Usuario';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-create">
    <div class="container">
        <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

        <div class="card">
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>

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
                        <?= $form->field($model, 'contrasena')->passwordInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'fecha_registro')->textInput(['type' => 'date']) ?>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-secondary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div> 