<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Калькулятор доставки',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-dark navbar-expand-sm bg-dark navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right nav-pills'],
        'dropDownCaret' => ' ',
        'items' => [
            Yii::$app->user->isGuest ? 
            ( ['label' => 'Войти в систему', 'url' => ['/user/security/login']] ) : (
            ['label' => Yii::$app->user->identity->username,
            'items' => [
                ['label' => 'История расчётов', 'url' => ['/home/history'], 'visible' => Yii::$app->user->isGuest === false],
                ['label' => 'Пользователи', 'url' => ['/user/admin'], 'visible' => Yii::$app->user->can('admin')],
                ['label' => 'Выход', 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post']],
                ],
            ]
            )
        ],
    ]);
    NavBar::end();
?>
        <div class="container">
            <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left"> Калькулятор стоимости поставки зерна <?= date('Y') ?></p>
        <p class="pull-right">
            <?=
            Yii::$app->user->isGuest ? ('Степанюк Антон Андреевич') 
            : (Yii::$app->user->identity->username)
            ?>
        </p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
