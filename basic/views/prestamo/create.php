<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Usuario;
use app\models\Libro;

/** @var yii\web\View $this */
/** @var app\models\Prestamo $model */

$this->title = 'Crear Préstamo';
$this->params['breadcrumbs'][] = ['label' => 'Préstamos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prestamo-create">
    <div class="container">
        <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

        <div class="card">
            <div class="card-body">
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
                            ArrayHelper::map(Libro::find()->all(), 'id_libro', 'titulo'),
                            ['prompt' => 'Seleccione un libro']
                        ) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'fecha_prestamo')->textInput(['type' => 'date']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'fecha_devolucion')->textInput(['type' => 'date']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'devuelto')->checkbox() ?>
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