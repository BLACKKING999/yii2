<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Rol;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$roles = ArrayHelper::map(Rol::find()->orderBy(['nivel_acceso' => SORT_DESC])->all(), 'id_rol', 'nombre_rol');
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
            
            <?php if (!$model->isNewRecord): ?>
                <div class="alert alert-info">
                    Deja la contraseña en blanco si no deseas cambiarla.
                </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>
            
            <?= $form->field($model, 'imagenFile')->fileInput(['accept' => 'image/*']) ?>
            
            <div class="alert alert-info">
                <i class="glyphicon glyphicon-info-sign"></i> Puedes cargar una imagen de perfil (opcional).
            </div>
        </div>
    </div>

    <?php if (Yii::$app->user->can('administrarUsuarios')): ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'id_rol')->dropDownList($roles, ['prompt' => 'Selecciona un rol']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'status')->dropDownList([
                    $model::STATUS_ACTIVE => 'Activo',
                    $model::STATUS_INACTIVE => 'Inactivo',
                    $model::STATUS_DELETED => 'Eliminado',
                ]) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
