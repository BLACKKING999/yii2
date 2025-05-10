<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $categoria app\models\Categoria */

$this->title = 'Libros de la categoría: ' . $categoria->nombre_categoria;
$this->params['breadcrumbs'][] = ['label' => 'Catálogo', 'url' => ['index']];
$this->params['breadcrumbs'][] = $categoria->nombre_categoria;
?>

<div class="categoria-libros">
    <div class="jumbotron" style="background-color: #f8f9fa;">
        <h1><?= Html::encode($categoria->nombre_categoria) ?></h1>
        <p><?= Html::encode($categoria->descripcion) ?></p>
        <p>
            <?= Html::a('Volver al catálogo', ['index'], ['class' => 'btn btn-primary']) ?>
        </p>
    </div>

    <?php Pjax::begin(); ?>
    
    <div class="libro-grid">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_libro_item',
            'layout' => "<div class='row'>{items}</div>\n{pager}",
            'options' => ['class' => 'list-view'],
            'emptyText' => '<div class="alert alert-info">No hay libros disponibles en esta categoría.</div>',
            'pager' => [
                'options' => ['class' => 'pagination'],
            ],
        ]) ?>
    </div>
    
    <?php Pjax::end(); ?>
</div>
