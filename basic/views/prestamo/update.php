<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Prestamo */

$this->title = 'Actualizar Préstamo: #' . $model->id_prestamo;
$this->params['breadcrumbs'][] = ['label' => 'Préstamos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Préstamo #' . $model->id_prestamo, 'url' => ['view', 'id' => $model->id_prestamo]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="prestamo-update">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
