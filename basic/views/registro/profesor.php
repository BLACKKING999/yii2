<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $profesor app\models\UsuarioProfesor */
/* @var $roles array */

$this->title = 'Registrar Nuevo Profesor';
$this->params['breadcrumbs'][] = ['label' => 'Registro de Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registro-profesor">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                <p><i class="glyphicon glyphicon-info-sign"></i> Complete el formulario con los datos del profesor.</p>
            </div>

            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

            <div class="row">
                <div class="col-md-6">
                    <h4>Información de Usuario</h4>
                    
                    <?= $form->field($user, 'username')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($user, 'password')->passwordInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($user, 'nombre')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($user, 'apellidos')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($user, 'id_rol')->dropDownList($roles, ['prompt' => 'Selecciona un rol']) ?>
                    
                    <?= $form->field($user, 'imagenFile')->fileInput() ?>
                </div>
                
                <div class="col-md-6">
                    <h4>Información de Profesor</h4>
                    
                    <?= $form->field($profesor, 'id_usuario_biblioteca')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($profesor, 'departamento')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($profesor, 'especialidad')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($profesor, 'oficina')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="form-group text-center">
                <?= Html::submitButton('Registrar Profesor', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
