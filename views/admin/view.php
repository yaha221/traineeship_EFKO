<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use nkostadinov\user\Module;
use Jenssegers\Date\Date;

$this->title = "{$model->DisplayName}";
$this->params['breadcrumbs'][] = ['label' => Yii::t(Module::I18N_CATEGORY, 'Пользователи'), 'url' => ['users']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="user-view">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a(Yii::t(Module::I18N_CATEGORY, 'Обновить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t(Module::I18N_CATEGORY, 'Удалить'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t(Module::I18N_CATEGORY, 'Вы действительно решили удалить пользователя?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'username',
                'email:email',
                [
                    'label' => 'Статус',
                    'attribute' => 'status',
                    'value' => function($model) {
                        return $model->status === 1 ? 'Активен' : 'Неактивен';
                    },
                ],
                [
                    'label' => 'Дата создания',
                    'attribute' => 'created_at',
                    'value' => function($model) {
                        return Date::parse($model->created_at)->format('d.m.Y H:i:s');
                    },
                ],
                [
                    'label' => 'Дата изменения',
                    'attribute' => 'updated_at',
                    'value' => function($model) {
                        return Date::parse($model->updated_at)->format('d.m.Y H:i:s');
                    },
                ],
                [
                    'label' => 'Дата входа',
                    'attribute' => 'last_login',
                    'value' => function($model) {
                        return Date::parse($model->last_login)->format('d.m.Y H:i:s');
                    },
                ],
            ],
        ]) ?>


    </div>