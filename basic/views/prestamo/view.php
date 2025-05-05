<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Prestamo */

$this->title = 'Préstamo #' . $model->id_prestamo;
$this->params['breadcrumbs'][] = ['label' => 'Préstamos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prestamo-view">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('<i class="fas fa-edit"></i> Actualizar', ['update', 'id' => $model->id_prestamo], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash-alt"></i> Eliminar', ['delete', 'id' => $model->id_prestamo], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => '¿Está seguro de eliminar este préstamo?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Volver', ['index'], ['class' => 'btn btn-secondary']) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id_prestamo',
                [
                    'attribute' => 'id_usuario',
                    'value' => $model->usuario->nombre,
                    'label' => 'Usuario'
                ],
                [
                    'attribute' => 'id_libro',
                    'value' => $model->libro->titulo,
                    'label' => 'Libro'
                ],
                'fecha_prestamo',
                'fecha_devolucion',
                [
                    'attribute' => 'devuelto',
                    'value' => $model->devuelto ? 'Sí' : 'No',
                    'label' => 'Devuelto'
                ],
            ],
        ]) ?>
    </div>
</div>
