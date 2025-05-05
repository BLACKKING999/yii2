<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Categoria $model */

$this->title = 'Crear Categoría';
$this->params['breadcrumbs'][] = ['label' => 'Categorías', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categoria-create">
    <div class="container">
        <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

        <div class="card">
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>

                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'nombre_categoria')->textInput(['maxlength' => true]) ?>
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