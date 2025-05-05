<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $authUrl string */

$this->title = 'Iniciar sesión con Google';
$this->params['breadcrumbs'][] = $this->title;

// Obtener la URL de autenticación con Google directamente del controlador
$authUrl = isset($authUrl) ? $authUrl : Url::to(['site/auth-google-redirect']);
?>
<div class="site-auth-google">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="card-body text-center">
                    <p>Haz clic en el botón para iniciar sesión con Google:</p>
                    
                    <div class="text-center">
                        <?= Html::a(
                            '<i class="fa fa-google"></i> Iniciar sesión con Google', 
                            $authUrl, 
                            ['class' => 'btn btn-danger btn-lg']
                        ) ?>
                    </div>
                    
                    <div class="mt-4">
                        <?= Html::a('Volver al inicio de sesión normal', ['site/login'], ['class' => 'btn btn-outline-secondary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
