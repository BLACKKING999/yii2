<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */
/* @var $permisos app\models\Permiso[] */

$this->title = $model->nombre_rol;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="rol-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_rol], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Asignar Permisos', ['asignar-permisos', 'id' => $model->id_rol], ['class' => 'btn btn-info']) ?>
        <?php if ($model->id_rol > 5): ?>
            <?= Html::a('Eliminar', ['delete', 'id' => $model->id_rol], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => '¿Estás seguro de que quieres eliminar este rol?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_rol',
            'nombre_rol',
            'descripcion',
            'nivel_acceso',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'label' => 'Creado en',
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'label' => 'Actualizado en',
            ],
        ],
    ]) ?>
    
    <h2>Permisos asignados</h2>
    
    <?php if (empty($permisos)): ?>
        <div class="alert alert-warning">Este rol no tiene permisos asignados.</div>
    <?php else: ?>
        <?= GridView::widget([
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $permisos,
                'pagination' => false,
            ]),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'nombre_permiso',
                'descripcion',
            ],
        ]); ?>
    <?php endif; ?>

</div>
