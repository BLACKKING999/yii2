<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Autor;
use app\models\Categoria;

/* @var $this yii\web\View */
/* @var $model app\models\Libro */

$this->title = 'Crear Libro';
$this->params['breadcrumbs'][] = ['label' => 'Libros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="libro-create">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data', 'class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-md-9\">{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'col-md-3 control-label'],
                ],
            ]); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'isbn')->textInput(['maxlength' => true, 'class' => 'form-control input-sm']) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'titulo')->textInput(['maxlength' => true, 'class' => 'form-control input-sm']) ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'id_autor')->dropDownList(
                            ArrayHelper::map(Autor::find()->all(), 'id_autor', 'nombre'),
                            [
                                'prompt' => 'Seleccione un autor',
                                'class' => 'form-control input-sm select2',
                            ]
                        ) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'id_categoria')->dropDownList(
                            ArrayHelper::map(Categoria::find()->all(), 'id_categoria', 'nombre'),
                            [
                                'prompt' => 'Seleccione una categoría',
                                'class' => 'form-control input-sm select2',
                            ]
                        ) ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'editorial')->textInput(['maxlength' => true, 'class' => 'form-control input-sm']) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'anio_publicacion')->textInput(['type' => 'number', 'class' => 'form-control input-sm']) ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'num_paginas')->textInput(['type' => 'number', 'class' => 'form-control input-sm']) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'idioma')->textInput(['maxlength' => true, 'class' => 'form-control input-sm']) ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'ubicacion_fisica')->textInput(['maxlength' => true, 'class' => 'form-control input-sm']) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'imagenFile')->fileInput(['class' => 'form-control input-sm']) ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= $form->field($model, 'descripcion')->textarea(['rows' => 6, 'class' => 'form-control input-sm']) ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <?= Html::submitButton('<i class="fa fa-save"></i> Guardar', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('<i class="fa fa-times"></i> Cancelar', ['index'], ['class' => 'btn btn-default']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$this->registerJs("
    $(function () {
        $('.select2').select2({
            theme: 'bootstrap',
            width: '100%'
        });
    });
");
?> 