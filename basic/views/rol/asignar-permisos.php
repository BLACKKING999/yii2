<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */
/* @var $allPermisos app\models\Permiso[] */
/* @var $permisosAsignados array */

$this->title = 'Asignar Permisos: ' . $model->nombre_rol;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre_rol, 'url' => ['view', 'id' => $model->id_rol]];
$this->params['breadcrumbs'][] = 'Asignar Permisos';
?>
<div class="rol-asignar-permisos">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Permisos disponibles</h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <?php foreach ($allPermisos as $permiso): ?>
                    <div class="col-md-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="permisos[]" value="<?= $permiso->id_permiso ?>" 
                                    <?= isset($permisosAsignados[$permiso->id_permiso]) ? 'checked' : '' ?>>
                                <?= Html::encode($permiso->nombre_permiso) ?>
                            </label>
                            <p class="help-block"><?= Html::encode($permiso->descripcion) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
