<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model app\models\Libro */
?>

<div class="card h-100">
    <div class="position-relative">
        <?= Html::img($model->imagenUrl, [
            'class' => 'card-img-top',
            'alt' => $model->titulo
        ]) ?>
        <span class="position-absolute top-0 end-0 m-2 badge <?= $model->disponible ? 'bg-success' : 'bg-danger' ?>">
            <?= $model->disponible ? 'Disponible' : 'No disponible' ?>
        </span>
    </div>
    
    <div class="card-body">
        <h5 class="card-title"><?= Html::encode($model->titulo) ?></h5>
        
        <div class="autor-categoria mb-2">
            <div><i class="fas fa-user"></i> <?= Html::encode($model->autor ? $model->autor->nombre_autor : 'Autor no especificado') ?></div>
            <div><i class="fas fa-tag"></i> <?= Html::encode($model->categoria ? $model->categoria->nombre_categoria : 'Categoría no especificada') ?></div>
        </div>
        
        <div class="card-text">
            <small class="text-muted">
                <i class="fas fa-book"></i> <?= Html::encode($model->editorial) ?><br>
                <i class="fas fa-calendar"></i> <?= Html::encode($model->anio_publicacion) ?>
            </small>
        </div>
    </div>
    
    <div class="card-footer bg-transparent border-top-0">
        <?= Html::a('Ver detalles', ['libro/view', 'id_libro' => $model->id_libro], [
            'class' => 'btn btn-primary btn-sm w-100',
            'data' => ['pjax' => 0]
        ]) ?>
    </div>
</div> 