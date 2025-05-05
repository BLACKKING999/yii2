<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Autor */

$this->title = 'Actualizar Autor: ' . $model->nombre_autor;
$this->params['breadcrumbs'][] = ['label' => 'Autores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre_autor, 'url' => ['view', 'id' => $model->id_autor]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="autor-update">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
