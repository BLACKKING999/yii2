<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Panel de Control';
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user->identity;
?>

<div class="site-dashboard">
    <div class="jumbotron">
        <h1>¡Bienvenido, <?= Html::encode($user->getNombreCompleto()) ?>!</h1>
        <p class="lead">Panel de control de la Biblioteca Virtual</p>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Mi Perfil</h3>
                </div>
                <div class="panel-body">
                    <p><strong>Usuario:</strong> <?= Html::encode($user->username) ?></p>
                    <p><strong>Nombre:</strong> <?= Html::encode($user->getNombreCompleto()) ?></p>
                    <p><strong>Email:</strong> <?= Html::encode($user->email) ?></p>
                    <p><strong>Rol:</strong> <?= Html::encode($user->rol->nombre_rol) ?></p>
                    <p><strong>Tipo de usuario:</strong> <?= Html::encode(ucfirst($user->getTipoUsuario())) ?></p>
                    
                    <p>
                        <?= Html::a('Editar Perfil', ['/user/update', 'id' => $user->id_usuario], ['class' => 'btn btn-primary']) ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Acciones disponibles</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?php if ($user->puedeAdministrarUsuarios()): ?>
                            <div class="col-md-4 text-center">
                                <div class="well">
                                    <h4>Usuarios</h4>
                                    <p><i class="glyphicon glyphicon-user" style="font-size: 24px;"></i></p>
                                    <?= Html::a('Gestionar Usuarios', ['/user/index'], ['class' => 'btn btn-info']) ?>
                                    <?= Html::a('Registrar Usuarios', ['/registro/index'], ['class' => 'btn btn-success', 'style' => 'margin-top: 5px;']) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($user->puedeAdministrarRoles()): ?>
                            <div class="col-md-4 text-center">
                                <div class="well">
                                    <h4>Roles</h4>
                                    <p><i class="glyphicon glyphicon-lock" style="font-size: 24px;"></i></p>
                                    <?= Html::a('Gestionar Roles', ['/rol/index'], ['class' => 'btn btn-info']) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($user->puedeAdministrarLibros()): ?>
                            <div class="col-md-4 text-center">
                                <div class="well">
                                    <h4>Libros</h4>
                                    <p><i class="glyphicon glyphicon-book" style="font-size: 24px;"></i></p>
                                    <?= Html::a('Gestionar Libros', ['/libro/index'], ['class' => 'btn btn-info']) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($user->puedeAdministrarUsuarios()): ?>
                            <div class="col-md-4 text-center">
                                <div class="well">
                                    <h4>Estudiantes</h4>
                                    <p><i class="glyphicon glyphicon-education" style="font-size: 24px;"></i></p>
                                    <?= Html::a('Gestionar Estudiantes', ['/usuario-estudiante/index'], ['class' => 'btn btn-info']) ?>
                                </div>
                            </div>
                            
                            <div class="col-md-4 text-center">
                                <div class="well">
                                    <h4>Profesores</h4>
                                    <p><i class="glyphicon glyphicon-blackboard" style="font-size: 24px;"></i></p>
                                    <?= Html::a('Gestionar Profesores', ['/usuario-profesor/index'], ['class' => 'btn btn-info']) ?>
                                </div>
                            </div>
                            
                            <div class="col-md-4 text-center">
                                <div class="well">
                                    <h4>Personal</h4>
                                    <p><i class="glyphicon glyphicon-briefcase" style="font-size: 24px;"></i></p>
                                    <?= Html::a('Gestionar Personal', ['/usuario-personal/index'], ['class' => 'btn btn-info']) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($user->puedeGestionarPrestamos()): ?>
                            <div class="col-md-4 text-center">
                                <div class="well">
                                    <h4>Préstamos</h4>
                                    <p><i class="glyphicon glyphicon-transfer" style="font-size: 24px;"></i></p>
                                    <?= Html::a('Gestionar Préstamos', ['/prestamo/index'], ['class' => 'btn btn-info']) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="col-md-4 text-center">
                            <div class="well">
                                <h4>Catálogo</h4>
                                <p><i class="glyphicon glyphicon-list-alt" style="font-size: 24px;"></i></p>
                                <?= Html::a('Ver Catálogo', ['/catalogo/index'], ['class' => 'btn btn-info']) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-center">
                            <div class="well">
                                <h4>Mis Préstamos</h4>
                                <p><i class="glyphicon glyphicon-book" style="font-size: 24px;"></i></p>
                                <?= Html::a('Mis Préstamos', ['/prestamo/mis-prestamos'], ['class' => 'btn btn-success']) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-center">
                            <div class="well">
                                <h4>Mis Reservas</h4>
                                <p><i class="glyphicon glyphicon-bookmark" style="font-size: 24px;"></i></p>
                                <?= Html::a('Mis Reservas', ['/prestamo/mis-reservas'], ['class' => 'btn btn-warning']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mostrar información específica según el tipo de usuario -->
    <?php if ($user->esEstudiante() && $estudiante = $user->getUsuarioEstudiante()->one()): ?>
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Información de Estudiante</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Carnet:</strong> <?= Html::encode($estudiante->carnet) ?></p>
                        <p><strong>Carrera:</strong> <?= Html::encode($estudiante->carrera) ?></p>
                        <p><strong>Semestre:</strong> <?= Html::encode($estudiante->semestre) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Préstamos activos:</strong> <span class="badge">0</span></p>
                        <p><strong>Libros reservados:</strong> <span class="badge">0</span></p>
                        <?= Html::a('Mis Préstamos', ['/prestamo/mis-prestamos'], ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif ($user->esProfesor() && $profesor = $user->getUsuarioProfesor()->one()): ?>
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Información de Profesor</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Especialidad:</strong> <?= Html::encode($profesor->especialidad) ?></p>
                        <p><strong>Departamento:</strong> <?= Html::encode($profesor->departamento) ?></p>
                        <p><strong>Oficina:</strong> <?= Html::encode($profesor->oficina) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Préstamos activos:</strong> <span class="badge">0</span></p>
                        <p><strong>Libros reservados:</strong> <span class="badge">0</span></p>
                        <?= Html::a('Mis Préstamos', ['/prestamo/mis-prestamos'], ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif ($user->getTipoUsuario() == 'personal' && $personal = $user->getUsuarioPersonal()->one()): ?>
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Información de Personal</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Departamento:</strong> <?= Html::encode($personal->departamento) ?></p>
                        <p><strong>Cargo:</strong> <?= Html::encode($personal->cargo) ?></p>
                        <p><strong>Fecha de contratación:</strong> <?= Yii::$app->formatter->asDate($personal->fecha_contratacion) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Préstamos activos:</strong> <span class="badge">0</span></p>
                        <p><strong>Libros reservados:</strong> <span class="badge">0</span></p>
                        <?= Html::a('Mis Préstamos', ['/prestamo/mis-prestamos'], ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
