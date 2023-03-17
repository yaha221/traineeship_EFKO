<?php

use yii\helpers\Html;

$this->title = 'Ошибка';
?>

<div class="site-error">

    <h1><?= $code ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

</div>