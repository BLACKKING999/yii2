<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $autor app\models\Autor */

$this->title = 'Libros del autor: ' . $autor->getNombreCompleto();
$this->params['breadcrumbs'][] = ['label' => 'Catálogo', 'url' => ['index']];
$this->params['breadcrumbs'][] = $autor->getNombreCompleto();
?>

<div class="autor-libros">
    <div class="jumbotron" style="background-color: #f8f9fa;">
        <h1><?= Html::encode($autor->getNombreCompleto()) ?></h1>
        <p><?= Html::encode($autor->biografia) ?></p>
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
            'emptyText' => '<div class="alert alert-info">No hay libros disponibles de este autor.</div>',
            'pager' => [
                'options' => ['class' => 'pagination'],
            ],
        ]) ?>
    </div>
    
    <?php Pjax::end(); ?>
</div>
