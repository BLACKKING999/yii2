<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Rol;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Actualizar Perfil';
$this->params['breadcrumbs'][] = ['label' => 'Mi Perfil', 'url' => ['view', 'id_usuario' => $model->id_usuario]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>

<div class="user-update">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                            <p class="help-block">Dejar en blanco si no desea cambiar la contraseña</p>
                        </div>
                    </div>

                    <?php if ($model->scenario === User::SCENARIO_UPDATE): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'id_rol')->dropDownList(
                                ArrayHelper::map(Rol::find()->orderBy(['nivel_acceso' => SORT_DESC])->all(), 'id_rol', 'nombre_rol'),
                                ['prompt' => 'Seleccione un rol']
                            ) ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'imagenFile')->fileInput() ?>
                            <p class="help-block">Formatos permitidos: PNG, JPG, JPEG. Tamaño máximo: 2MB</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Cancelar', ['view', 'id_usuario' => $model->id_usuario], ['class' => 'btn btn-default']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Información de Ayuda</h3>
                </div>
                <div class="panel-body">
                    <p><strong>Nota:</strong> Todos los campos marcados con * son obligatorios.</p>
                    <p>La contraseña debe tener al menos 6 caracteres.</p>
                    <p>La imagen de perfil debe ser en formato JPG, JPEG o PNG y no debe exceder 2MB.</p>
                </div>
            </div>
        </div>
    </div>
</div> 