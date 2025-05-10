<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $usuarios app\models\Usuario[] */

$this->title = 'Vincular Usuario del Sistema con Usuario de Biblioteca';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Vincular Usuario';
?>
<div class="user-vincular">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <p><i class="fas fa-info-circle"></i> Esta función permite vincular un usuario del sistema (<?= Html::encode($model->username) ?>) 
        con un usuario existente de la biblioteca. Esto es útil para permitir que los administradores o personal que usa el sistema
        también puedan ser prestatarios de libros.</p>
    </div>

    <?php if ($model->id_usuario_biblioteca): ?>
        <div class="alert alert-warning">
            <p><i class="fas fa-exclamation-triangle"></i> Este usuario ya está vinculado con un usuario de biblioteca. 
            Si selecciona otro usuario de biblioteca, se reemplazará la vinculación actual.</p>
        </div>
    <?php endif; ?>

    <div class="user-form">
        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group field-usuario">
            <label class="control-label" for="id_usuario_biblioteca">Usuario de Biblioteca</label>
            <select id="id_usuario_biblioteca" class="form-control" name="id_usuario_biblioteca">
                <option value="">Seleccione un usuario de biblioteca...</option>
                <?php foreach ($usuarios as $usuario): ?>
                <option value="<?= $usuario->id_usuario ?>" <?= $model->id_usuario_biblioteca == $usuario->id_usuario ? 'selected' : '' ?>>
                    <?= Html::encode($usuario->id_usuario . ' - ' . $usuario->nombre . ' (' . $usuario->correo . ')') ?>
                </option>
                <?php endforeach; ?>
            </select>
            <div class="help-block">Seleccione el usuario de biblioteca que desea vincular con este usuario del sistema.</div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Vincular Usuario', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Cancelar', ['view', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
