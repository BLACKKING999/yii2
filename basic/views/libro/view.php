<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Libro */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Libros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="libro-view">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('<i class="fas fa-edit"></i> Actualizar', ['update', 'id' => $model->id_libro], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash-alt"></i> Eliminar', ['delete', 'id' => $model->id_libro], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => '¿Está seguro de eliminar este libro?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Volver', ['index'], ['class' => 'btn btn-secondary']) ?>
        </p>
        
        <div class="row">
            <div class="col-md-4">
                <?php if ($model->imagen_portada): ?>
                    <img src="<?= $model->getImagenUrl() ?>" alt="Portada del libro" class="img-fluid img-thumbnail mb-3" style="max-width: 100%;">
                <?php else: ?>
                    <div class="alert alert-info">
                        No hay imagen de portada disponible
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id_libro',
                        'titulo',
                        [
                            'attribute' => 'id_autor',
                            'value' => $model->autor->nombre_autor,
                            'label' => 'Autor'
                        ],
                        [
                            'attribute' => 'id_categoria',
                            'value' => $model->categoria->nombre_categoria,
                            'label' => 'Categoría'
                        ],
                        'anio_publicacion',
                        [
                            'attribute' => 'disponible',
                            'value' => $model->disponible ? 'Sí' : 'No',
                            'label' => 'Disponible'
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
