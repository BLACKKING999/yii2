<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuarioPersonalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Personal Administrativo';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-personal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Registrar Nuevo Personal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
