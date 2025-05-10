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

<div class="user-form-update">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true])->hint('Deja este campo en blanco si no deseas cambiar la contraseña.') ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>
            
            <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
            
            <?= $form->field($model, 'direccion')->textarea(['rows' => 3]) ?>
            
            <?php if ($model->imagen): ?>
                <div class="form-group">
                    <label>Imagen Actual</label>
                    <div>
                        <img src="<?= $model->getImagenUrl() ?>" alt="Imagen de perfil" style="max-width: 150px; max-height: 150px;">
                    </div>
                </div>
            <?php endif; ?>
            
            <?= $form->field($model, 'imagenFile')->fileInput()->hint('Selecciona una nueva imagen para reemplazar la actual.') ?>
        </div>
    </div>

    <?php if (Yii::$app->user->identity->puedeAdministrarUsuarios()): ?>
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
        <?= Html::submitButton('Actualizar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
