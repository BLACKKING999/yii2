<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Categoria */

$this->title = $model->nombre_categoria;
$this->params['breadcrumbs'][] = ['label' => 'Categorías', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categoria-view">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('<i class="fas fa-edit"></i> Actualizar', ['update', 'id' => $model->id_categoria], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash-alt"></i> Eliminar', ['delete', 'id' => $model->id_categoria], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => '¿Está seguro de eliminar esta categoría?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Volver', ['index'], ['class' => 'btn btn-secondary']) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id_categoria',
                'nombre_categoria',
            ],
        ]) ?>
    </div>
</div>
