<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuarioEstudianteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Estudiantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-estudiante-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Registrar Nuevo Estudiante', ['create'], ['class' => 'btn btn-success']) ?>
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
            'carnet',
            'carrera',
            'semestre',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'label' => 'Registro',
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
