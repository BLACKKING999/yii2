<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioPersonal */

$this->title = $model->usuario->nombre . ' ' . $model->usuario->apellido;
$this->params['breadcrumbs'][] = ['label' => 'Personal Administrativo', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="usuario-personal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_personal], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_personal], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que quieres eliminar este registro de personal?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_personal',
            [
                'attribute' => 'usuario.username',
                'label' => 'Usuario',
            ],
            [
                'attribute' => 'usuario.nombre',
                'label' => 'Nombre',
            ],
            [
                'attribute' => 'usuario.apellido',
                'label' => 'Apellido',
            ],
            [
                'attribute' => 'usuario.email',
                'label' => 'Email',
            ],
            'departamento',
            'cargo',
            [
                'attribute' => 'fecha_contratacion',
                'format' => 'date',
                'label' => 'Fecha de Contratación',
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'label' => 'Registro',
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'label' => 'Última actualización',
            ],
        ],
    ]) ?>

</div>
