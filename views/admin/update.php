<?php

use yii\helpers\Html;
use yii\web\View;
use nkostadinov\user\Module;


$this->title = Yii::t(Module::I18N_CATEGORY, 'Обновить {modelClass}: ', [
    'modelClass' => 'пользователя',
]) . " $model->username";
$this->params['breadcrumbs'][] = ['label' => Yii::t(Module::I18N_CATEGORY, 'Пользователи'), 'url' => ['users']];
$this->params['breadcrumbs'][] = ['label' => "$model->username ", 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t(Module::I18N_CATEGORY, 'Редактировать');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
