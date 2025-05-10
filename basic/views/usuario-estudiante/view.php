<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioEstudiante */

$this->title = $model->usuario->nombre . ' ' . $model->usuario->apellido;
$this->params['breadcrumbs'][] = ['label' => 'Estudiantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="usuario-estudiante-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_estudiante], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_estudiante], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que quieres eliminar este estudiante?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_estudiante',
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
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'label' => 'Última actualización',
            ],
        ],
    ]) ?>

</div>
