<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Libro */

$this->title = 'Actualizar Libro: ' . $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Libros', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->titulo, 'url' => ['view', 'id' => $model->id_libro]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="libro-update">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
