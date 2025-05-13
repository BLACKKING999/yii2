<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use app\models\Libro;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LibroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $categorias app\models\Categoria[] */
/* @var $autores app\models\Autor[] */

$this->title = 'Catálogo de Libros';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="catalogo-index">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4">
            <?= $this->render('_search', ['model' => $searchModel]) ?>
        </div>
    </div>

    <div class="row">
        <?php
        $dataProvider = new ActiveDataProvider([
            'query' => Libro::find()->with(['autor', 'categoria']),
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_libro_card',
            'layout' => "{items}\n<div class='text-center'>{pager}</div>",
            'options' => ['class' => 'row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4'],
            'itemOptions' => ['class' => 'col'],
            'pager' => [
                'options' => ['class' => 'pagination justify-content-center mt-4'],
                'linkContainerOptions' => ['class' => 'page-item'],
                'linkOptions' => ['class' => 'page-link'],
                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
            ],
        ]);
        ?>
    </div>
</div>

<?php
// Estilos CSS personalizados
$css = <<<CSS
.card {
    height: 100%;
    transition: transform 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.card-text {
    font-size: 0.9rem;
    color: #666;
}

.badge {
    font-size: 0.8rem;
    padding: 0.4em 0.8em;
}

.autor-categoria {
    font-size: 0.85rem;
    color: #666;
}

.disponible {
    color: #28a745;
}

.no-disponible {
    color: #dc3545;
}
CSS;

$this->registerCss($css);
?>
