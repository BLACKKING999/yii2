<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Mi Perfil';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Foto de Perfil</h3>
                </div>
                <div class="panel-body text-center">
                    <?= Html::img($model->getImagenUrl(), ['class' => 'img-thumbnail', 'style' => 'max-width: 200px;']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Información Personal</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'username',
                            'nombre',
                            'apellidos',
                            'email',
                            'rol.nombre_rol',
                            [
                                'attribute' => 'fecha_registro',
                                'format' => 'date',
                            ],
                        ],
                    ]) ?>

                    <div class="text-right">
                        <?= Html::a('Actualizar Perfil', ['update', 'id_usuario' => $model->id_usuario], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>

            <?php if ($model->esEstudiante()): ?>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Información de Estudiante</h3>
                    </div>
                    <div class="panel-body">
                        <?= DetailView::widget([
                            'model' => $model->usuarioEstudiante,
                            'attributes' => [
                                'carnet',
                                'carrera',
                                'semestre',
                            ],
                        ]) ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($model->esProfesor()): ?>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Información de Profesor</h3>
                    </div>
                    <div class="panel-body">
                        <?= DetailView::widget([
                            'model' => $model->usuarioProfesor,
                            'attributes' => [
                                'especialidad',
                                'departamento',
                                'oficina',
                            ],
                        ]) ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($model->esPersonal()): ?>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Información de Personal</h3>
                    </div>
                    <div class="panel-body">
                        <?= DetailView::widget([
                            'model' => $model->usuarioPersonal,
                            'attributes' => [
                                'departamento',
                                'cargo',
                                'fecha_contratacion:date',
                            ],
                        ]) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
