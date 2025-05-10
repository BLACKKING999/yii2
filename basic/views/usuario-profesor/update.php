<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioProfesor */
/* @var $userModel app\models\User */

$this->title = 'Actualizar Profesor: ' . $model->usuario->nombre . ' ' . $model->usuario->apellido;
$this->params['breadcrumbs'][] = ['label' => 'Profesores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usuario->nombre . ' ' . $model->usuario->apellido, 'url' => ['view', 'id' => $model->id_profesor]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="usuario-profesor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Datos de Usuario</h3>
                </div>
                <div class="panel-body">
                    <?= $this->render('/user/_form_update', [
                        'model' => $userModel,
                    ]) ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Datos de Profesor</h3>
                </div>
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

</div>
