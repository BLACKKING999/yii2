<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Autor;
use app\models\Categoria;

/** @var yii\web\View $this */
/** @var app\models\Libro $model */

$this->title = 'Crear Libro';
$this->params['breadcrumbs'][] = ['label' => 'Libros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="libro-create">
    <div class="container">
        <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

        <div class="card">
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>

                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'id_autor')->dropDownList(
                            ArrayHelper::map(Autor::find()->all(), 'id_autor', 'nombre_autor'),
                            ['prompt' => 'Seleccione un autor']
                        ) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'id_categoria')->dropDownList(
                            ArrayHelper::map(Categoria::find()->all(), 'id_categoria', 'nombre_categoria'),
                            ['prompt' => 'Seleccione una categoría']
                        ) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'anio_publicacion')->textInput(['type' => 'number']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'disponible')->checkbox() ?>
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