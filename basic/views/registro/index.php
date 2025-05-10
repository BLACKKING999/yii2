<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Registro de Nuevos Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registro-index">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                <p><i class="glyphicon glyphicon-info-sign"></i> Seleccione el tipo de usuario que desea registrar.</p>
            </div>
            
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="well">
                        <h3>Estudiante</h3>
                        <p><i class="glyphicon glyphicon-education" style="font-size: 48px;"></i></p>
                        <p>Registrar un nuevo estudiante en el sistema.</p>
                        <?= Html::a('Registrar Estudiante', ['registro/estudiante'], ['class' => 'btn btn-primary btn-lg']) ?>
                    </div>
                </div>
                
                <div class="col-md-4 text-center">
                    <div class="well">
                        <h3>Profesor</h3>
                        <p><i class="glyphicon glyphicon-briefcase" style="font-size: 48px;"></i></p>
                        <p>Registrar un nuevo profesor en el sistema.</p>
                        <?= Html::a('Registrar Profesor', ['registro/profesor'], ['class' => 'btn btn-success btn-lg']) ?>
                    </div>
                </div>
                
                <div class="col-md-4 text-center">
                    <div class="well">
                        <h3>Personal</h3>
                        <p><i class="glyphicon glyphicon-user" style="font-size: 48px;"></i></p>
                        <p>Registrar nuevo personal administrativo.</p>
                        <?= Html::a('Registrar Personal', ['registro/personal'], ['class' => 'btn btn-info btn-lg']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
