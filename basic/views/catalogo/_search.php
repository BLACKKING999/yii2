<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Autor;
use app\models\Categoria;

/* @var $model app\models\LibroSearch */
?>

<div class="libro-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'form-inline',
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="input-group">
        <?= $form->field($model, 'titulo')->textInput([
            'placeholder' => 'Buscar por título...',
            'class' => 'form-control'
        ])->label(false) ?>
        
        <?= $form->field($model, 'id_categoria')->dropDownList(
            ArrayHelper::map(Categoria::find()->orderBy('nombre_categoria')->all(), 'id_categoria', 'nombre_categoria'),
            [
                'prompt' => 'Todas las categorías',
                'class' => 'form-select ms-2'
            ]
        )->label(false) ?>
        
        <?= $form->field($model, 'id_autor')->dropDownList(
            ArrayHelper::map(Autor::find()->orderBy('nombre_autor')->all(), 'id_autor', 'nombre_autor'),
            [
                'prompt' => 'Todos los autores',
                'class' => 'form-select ms-2'
            ]
        )->label(false) ?>
        
        <?= $form->field($model, 'disponible')->dropDownList([
            '' => 'Todos',
            '1' => 'Disponibles',
            '0' => 'No disponibles',
        ], [
            'class' => 'form-select ms-2'
        ])->label(false) ?>

        <div class="input-group-append ms-2">
            <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-times"></i>', ['index'], ['class' => 'btn btn-secondary ms-2']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div> 