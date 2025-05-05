<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-view">
    <div class="row">
        <div class="col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4 text-right">
            <?= Html::a('Actualizar', ['update', 'id' => $model->id_usuario], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Eliminar', ['delete', 'id' => $model->id_usuario], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => '¿Está seguro de eliminar este elemento?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <?php if ($model->imagen_perfil): ?>
                <img src="<?= $model->getImagenUrl() ?>" alt="Imagen de perfil" class="img-thumbnail mb-3" style="max-width: 100%;">
            <?php else: ?>
                <div class="alert alert-info">
                    No hay imagen de perfil disponible
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id_usuario',
                    'nombre',
                    'correo',
                    [
                        'attribute' => 'es_google',
                        'format' => 'boolean',
                    ],
                    [
                        'attribute' => 'fecha_registro',
                        'format' => ['date', 'php:d/m/Y H:i'],
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
