<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model app\models\Libro */
?>

<div class="col-md-4">
    <div class="panel panel-default libro-card">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Html::encode($model->titulo) ?></h3>
        </div>
        <div class="panel-body">
            <div class="libro-imagen">
                <?php if ($model->imagen_portada): ?>
                    <img src="<?= Yii::getAlias('@web/uploads/libros/') . $model->imagen_portada ?>" alt="<?= Html::encode($model->titulo) ?>">
                <?php else: ?>
                    <img src="<?= Yii::getAlias('@web/img/no-book-cover.png') ?>" alt="Portada no disponible">
                <?php endif; ?>
                
                <div class="libro-disponible <?= $model->disponible ? 'label-success' : 'label-danger' ?>">
                    <?= $model->disponible ? 'Disponible' : 'No disponible' ?>
                </div>
            </div>
            
            <div class="libro-info mt-3">
                <?php if ($model->autor): ?>
                    <p><strong>Autor:</strong> 
                        <a href="<?= Url::to(['catalogo/autor', 'id' => $model->id_autor]) ?>">
                            <?= Html::encode($model->autor->getNombreCompleto()) ?>
                        </a>
                    </p>
                <?php endif; ?>
                
                <?php if ($model->categoria): ?>
                    <p><strong>Categoría:</strong> 
                        <a href="<?= Url::to(['catalogo/categoria', 'id' => $model->id_categoria]) ?>">
                            <?= Html::encode($model->categoria->nombre_categoria) ?>
                        </a>
                    </p>
                <?php endif; ?>
                
                <p><strong>Año:</strong> <?= Html::encode($model->anio_publicacion) ?></p>
            </div>
        </div>
        <div class="panel-footer">
            <?= Html::a('Ver detalles', ['catalogo/view', 'id' => $model->id_libro], ['class' => 'btn btn-primary btn-block']) ?>
        </div>
    </div>
</div>
