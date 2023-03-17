<?php

use yii\helpers\Html;
use yii\web\View;
use nkostadinov\user\Module;



$this->title = Yii::t(Module::I18N_CATEGORY, 'Создать {modelClass}', [
    'modelClass' => 'пользователя',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(Module::I18N_CATEGORY, 'Пользователи'), 'url' => ['users']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
