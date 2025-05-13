<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Autor;
use app\models\Categoria;

/* @var $this yii\web\View */
/* @var $model app\models\Libro */

$this->title = 'Actualizar Libro: ' . $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Libros', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->titulo, 'url' => ['view', 'id_libro' => $model->id_libro]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>

<div class="libro-update">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class='col-md-9'>{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'col-md-3 control-label'],
                ],
            ]); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'id_autor')->dropDownList(
                        ArrayHelper::map(Autor::find()->orderBy('nombre_autor')->all(), 'id_autor', 'nombre_autor'),
                        ['prompt' => 'Seleccione un autor', 'class' => 'form-control select2']
                    ) ?>
                    <?= $form->field($model, 'id_categoria')->dropDownList(
                        ArrayHelper::map(Categoria::find()->orderBy('nombre_categoria')->all(), 'id_categoria', 'nombre_categoria'),
                        ['prompt' => 'Seleccione una categoría', 'class' => 'form-control select2']
                    ) ?>
                    <?= $form->field($model, 'editorial')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'anio_publicacion')->textInput(['type' => 'number']) ?>
                    <?= $form->field($model, 'num_paginas')->textInput(['type' => 'number']) ?>
                    <?= $form->field($model, 'idioma')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'ubicacion_fisica')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'imagenFile')->fileInput(['class' => 'form-control']) ?>
                    <?php if ($model->imagen_portada): ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Imagen Actual</label>
                            <div class="col-md-9">
                                <?= Html::img($model->getImagenUrl(), ['class' => 'img-thumbnail', 'style' => 'max-width: 200px;']) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <?= Html::submitButton('<i class="fa fa-save"></i> Guardar', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('<i class="fa fa-times"></i> Cancelar', ['view', 'id_libro' => $model->id_libro], ['class' => 'btn btn-default']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$this->registerJs("
    $(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: 'Seleccione una opción',
            allowClear: true
        });
    });
");
?>
