<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PasswordChangeForm */

$this->title = 'Cambiar Contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-change-password">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'currentPassword')->passwordInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'confirmPassword')->passwordInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Cambiar Contraseña', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
