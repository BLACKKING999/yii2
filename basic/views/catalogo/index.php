<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LibroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $categorias app\models\Categoria[] */
/* @var $autores app\models\Autor[] */

$this->title = 'Catálogo de Libros';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="catalogo-index">
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Filtros</h3>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1
                        ],
                    ]); ?>

                    <?= $form->field($searchModel, 'titulo')->textInput(['placeholder' => 'Buscar por título...']) ?>
                    
                    <?= $form->field($searchModel, 'id_categoria')->dropDownList(
                        \yii\helpers\ArrayHelper::map($categorias, 'id_categoria', 'nombre_categoria'),
                        ['prompt' => 'Todas las categorías']
                    ) ?>
                    
                    <?= $form->field($searchModel, 'id_autor')->dropDownList(
                        \yii\helpers\ArrayHelper::map($autores, 'id_autor', function($autor) {
                            return $autor->getNombreCompleto();
                        }),
                        ['prompt' => 'Todos los autores']
                    ) ?>
                    
                    <?= $form->field($searchModel, 'disponible')->dropDownList([
                        '' => 'Todos',
                        '1' => 'Disponibles',
                        '0' => 'No disponibles',
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Limpiar', ['index'], ['class' => 'btn btn-default']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Categorías</h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <?php foreach ($categorias as $categoria): ?>
                            <a href="<?= Url::to(['catalogo/categoria', 'id' => $categoria->id_categoria]) ?>" class="list-group-item">
                                <span class="badge"><?= $categoria->getLibrosCount() ?></span>
                                <?= Html::encode($categoria->nombre_categoria) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Autores Destacados</h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <?php foreach (array_slice($autores, 0, 10) as $autor): ?>
                            <a href="<?= Url::to(['catalogo/autor', 'id' => $autor->id_autor]) ?>" class="list-group-item">
                                <?= Html::encode($autor->getNombreCompleto()) ?>
                            </a>
                        <?php endforeach; ?>
                        <?php if (count($autores) > 10): ?>
                            <a href="#" class="list-group-item text-center">Ver todos los autores</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <h1><?= Html::encode($this->title) ?></h1>
            
            <?php Pjax::begin(); ?>
            
            <div class="libro-grid">
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemOptions' => ['class' => 'item'],
                    'itemView' => '_libro_item',
                    'layout' => "<div class='row'>{items}</div>\n{pager}",
                    'options' => ['class' => 'list-view'],
                    'pager' => [
                        'options' => ['class' => 'pagination'],
                    ],
                ]) ?>
            </div>
            
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<style>
.libro-card {
    height: 450px;
    margin-bottom: 20px;
    position: relative;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.libro-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.libro-card .panel-heading {
    height: 80px;
    overflow: hidden;
}

.libro-card .panel-body {
    height: 280px;
    overflow: hidden;
}

.libro-card .libro-imagen {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}

.libro-card .libro-imagen img {
    max-height: 200px;
    max-width: 100%;
    object-fit: contain;
}

.libro-card .libro-disponible {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 10px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: bold;
}

.libro-card .panel-footer {
    height: 60px;
    overflow: hidden;
}
</style>
