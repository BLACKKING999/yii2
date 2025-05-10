<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <p><i class="fas fa-info-circle"></i> Complete los siguientes campos para iniciar sesión o utilice uno de nuestros métodos de autenticación rápida.</p>
    </div>
    
    <div class="row mb-4">
        <div class="col-lg-5">
            <a href="<?= Url::to(['site/auth-google']) ?>" class="btn btn-danger btn-block">
                <i class="fab fa-google"></i> Iniciar sesión con Google
            </a>
        </div>
    </div>
    
    <p>O inicie sesión con su cuenta tradicional:</p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Usuario o Correo Electrónico') ?>
            <div class="hint-block">Puede usar su nombre de usuario o correo electrónico para iniciar sesión.</div>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

            <div class="mt-4 alert alert-secondary">
                <p>Opciones adicionales:</p>
                <ul>
                    <li>¿No tiene una cuenta? <a href="<?= Url::to(['usuario/create']) ?>">Regístrese como usuario de la biblioteca</a>.</li>
                    <li>¿Olvidó su contraseña? Contacte a un administrador para restablecerla.</li>
                </ul>
            </div>

        </div>
    </div>
</div>
