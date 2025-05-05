<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<!-- Botón para mostrar/ocultar sidebar -->
<button type="button" id="sidebarCollapse">
    <i class="fas fa-bars"></i>
</button>

<!-- Overlay para efecto oscuro cuando el sidebar está activo (mobile) -->
<div class="overlay"></div>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-header">
        <h3>Biblioteca Virtual</h3>
    </div>

    <ul class="list-unstyled components sidebar-nav">
        <li>
            <a href="<?= Yii::$app->homeUrl ?>">
                <i class="fas fa-home"></i> Inicio
            </a>
        </li>
        <li>
            <a href="#librosSubmenu" class="dropdown-toggle" data-bs-toggle="collapse" aria-expanded="false">
                <i class="fas fa-book"></i> Libros
                <i class="fas fa-angle-down float-end mt-1"></i>
            </a>
            <ul class="collapse list-unstyled" id="librosSubmenu">
                <li>
                    <a href="<?= \yii\helpers\Url::to(['/libro/index']) ?>">
                        <i class="fas fa-list"></i> Ver Libros
                    </a>
                </li>
                <li>
                    <a href="<?= \yii\helpers\Url::to(['/libro/create']) ?>">
                        <i class="fas fa-plus"></i> Nuevo Libro
                    </a>
                </li>
                <li>
                    <a href="<?= \yii\helpers\Url::to(['/autor/index']) ?>">
                        <i class="fas fa-user-edit"></i> Autores
                    </a>
                </li>
                <li>
                    <a href="<?= \yii\helpers\Url::to(['/categoria/index']) ?>">
                        <i class="fas fa-tags"></i> Categorías
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#usuariosSubmenu" class="dropdown-toggle" data-bs-toggle="collapse" aria-expanded="false">
                <i class="fas fa-users"></i> Usuarios
                <i class="fas fa-angle-down float-end mt-1"></i>
            </a>
            <ul class="collapse list-unstyled" id="usuariosSubmenu">
                <li>
                    <a href="<?= \yii\helpers\Url::to(['/usuario/index']) ?>">
                        <i class="fas fa-list"></i> Ver Usuarios
                    </a>
                </li>
                <li>
                    <a href="<?= \yii\helpers\Url::to(['/usuario/create']) ?>">
                        <i class="fas fa-user-plus"></i> Nuevo Usuario
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#prestamosSubmenu" class="dropdown-toggle" data-bs-toggle="collapse" aria-expanded="false">
                <i class="fas fa-exchange-alt"></i> Préstamos
                <i class="fas fa-angle-down float-end mt-1"></i>
            </a>
            <ul class="collapse list-unstyled" id="prestamosSubmenu">
                <li>
                    <a href="<?= \yii\helpers\Url::to(['/prestamo/index']) ?>">
                        <i class="fas fa-list"></i> Ver Préstamos
                    </a>
                </li>
                <li>
                    <a href="<?= \yii\helpers\Url::to(['/prestamo/create']) ?>">
                        <i class="fas fa-plus"></i> Nuevo Préstamo
                    </a>
                </li>
            </ul>
        </li>
        <li class="mt-5">
            <?php if (Yii::$app->user->isGuest): ?>
                <a href="<?= \yii\helpers\Url::to(['/site/login']) ?>">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
            <?php else: ?>
                <a href="<?= \yii\helpers\Url::to(['/site/logout']) ?>" data-method="post">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión (<?= Yii::$app->user->identity->username ?>)
                </a>
            <?php endif; ?>
        </li>
    </ul>
</nav>

<!-- Header principal -->
<header class="main-header">
    <div class="container">
        <a class="logo" href="<?= Yii::$app->homeUrl ?>">
            <i class="fas fa-book-reader"></i> Biblioteca Virtual
        </a>

        <?php if (!Yii::$app->user->isGuest): ?>
            <div class="user-info text-white">
                <i class="fas fa-user-circle"></i> 
                Bienvenido, <?= Html::encode(Yii::$app->user->identity->username) ?>
            </div>
        <?php endif; ?>
    </div>
</header>

<!-- Contenido principal -->
<div id="content">
    <main class="flex-shrink-0" role="main">
        <div class="container mt-4">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="float-start">&copy; Biblioteca Virtual <?= date('Y') ?></p>
                </div>
                <div class="col-md-6">
                    <p class="float-end">Desarrollado con <i class="fas fa-heart text-danger"></i> usando Yii Framework</p>
                </div>
            </div>
        </div>
    </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
