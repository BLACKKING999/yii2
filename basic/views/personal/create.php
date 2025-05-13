<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioPersonal */

$this->title = 'Registrar Nuevo Personal';
$this->params['breadcrumbs'][] = ['label' => 'Gestión de Personal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-personal-create">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <?= $this->render('_form', [
                'model' => $model,
                'userModel' => $userModel,
            ]) ?>
        </div>
    </div>
</div> 