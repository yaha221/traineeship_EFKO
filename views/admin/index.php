<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\web\JsExpression;
use kartik\select2\Select2;
use kartik\daterange\DateRangePicker;
use nkostadinov\user\Module;
use Jenssegers\Date\Date;

$this->title = Yii::t(Module::I18N_CATEGORY, 'Пользователи');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t(Module::I18N_CATEGORY, 'Создать {modelClass}', [
            'modelClass' => 'пользователя',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t(Module::I18N_CATEGORY, 'Обновить {modelClass}', [
            'modelClass' => 'роли',
        ]), ['/admin/role'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => "Показаны пользователи с {begin, number} по {end, number} из {totalCount, number}",
        'emptyText' => 'Ничего не найдено',
        'columns' => [
            'id',
            [
                'attribute' => 'username',
                'visible' => \Yii::$app->user->can('admin'),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'username',
                    'data' => $repository->findUsernamesForUsers(),
                    'language' => 'ru',
                    'size' => 'sm',
                    'options' => [
                        'prompt' => 'Введите имя',
                    ],
                    'pluginOptions' => [
                        'minimumInputLength' => 3,
                        'allowClear' => true,
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['/admin/usernamesearch']),
                            'dataType' => 'json',
                            'data' =>  new JsExpression('function (params) { return {q:params.term}; }'),
                            'delay' => 250,
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function (model) { return model.text; }'),
                        'templateSelection' => new JsExpression('function (username) { return username.text; }'),
                    ],
                ])
            ],
            [
                'attribute' => 'email',
                'visible' => \Yii::$app->user->can('admin'),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'email',
                    'data' => $repository->findEmails(),
                    'language' => 'ru',
                    'size' => 'sm',
                    'options' => [
                        'prompt' => 'Введите почту',
                    ],
                    'pluginOptions' => [
                        'minimumInputLength' => 3,
                        'allowClear' => true,
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['/admin/emailsearch']),
                            'dataType' => 'json',
                            'data' =>  new JsExpression('function (params) { return {q:params.term}; }'),
                            'delay' => 250,
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function (model) { return model.text; }'),
                        'templateSelection' => new JsExpression('function (email) { return email.text; }'),
                    ],
                ])
            ],
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status === 1 ? 'Активен' : 'Неактивен';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => [
                        0 => 'Неактивен',
                        1 => 'Активен',
                    ],
                    'attribute' => 'status',
                    'size' => 'sm',
                    'options' => [
                        'placeholder' => 'Выберите статус',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model) {
                    return Date::parse($model->created_at)->format('d.m.Y H:i:s');
                },
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'language' => 'ru-RU',
                    'attribute' => 'createTimeRange',
                    'presetDropdown' => true,
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'cancelButtonClasses' => 'btn-secondary',
                        'language' => 'ru',
                        'timePicker24Hour' => true,
                        'timePicker' => true,
                        'timePickerIncrement' => 10,
                        'locale' => [
                            'format' => 'd.m.Y H:i:s',
                            'separator' => ' - ',
                        ],
                        'opens' => 'right',
                    ]
                ])

            ],
            [
                'attribute' => 'last_login',
                'value' => function($model) {
                    return Date::parse($model->last_login)->format('d.m.Y H:i:s');
                },
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'language' => 'ru-RU',
                    'attribute' => 'lastLoginTimeRange',
                    'presetDropdown' => true,
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'cancelButtonClasses' => 'btn-secondary',
                        'language' => 'ru',
                        'timePicker24Hour' => true,
                        'timePicker' => true,
                        'timePickerIncrement' => 10,
                        'locale' => [
                            'format' => 'd.m.Y H:i:s',
                            'separator' => ' - ',
                        ],
                        'opens' => 'right',
                    ]
                ])

            ],
            [
                'attribute' => 'last_login_ip',
                'visible' => \Yii::$app->user->can('admin'),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'last_login_ip',
                    'data' => $repository->findLastLoginIp(),
                    'language' => 'ru',
                    'size' => 'sm',
                    'options' => [
                        'prompt' => 'Введите ip',
                    ],
                    'pluginOptions' => [
                        'minimumInputLength' => 3,
                        'allowClear' => true,
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['/admin/lastloginipsearch']),
                            'dataType' => 'json',
                            'data' =>  new JsExpression('function (params) { return {q:params.term}; }'),
                            'delay' => 250,
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function (model) { return model.text; }'),
                        'templateSelection' => new JsExpression('function (last_login_ip) { return last_login_ip.text; }'),
                    ],
                ])
            ],
            [
                'attribute' => 'register_ip',
                'visible' => \Yii::$app->user->can('admin'),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'register_ip',
                    'data' => $repository->findRegisterIp(),
                    'language' => 'ru',
                    'size' => 'sm',
                    'options' => [
                        'prompt' => 'Введите ip',
                    ],
                    'pluginOptions' => [
                        'minimumInputLength' => 3,
                        'allowClear' => true,
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['/admin/registeripsearch']),
                            'dataType' => 'json',
                            'data' =>  new JsExpression('function (params) { return {q:params.term}; }'),
                            'delay' => 250,
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function (model) { return model.text; }'),
                        'templateSelection' => new JsExpression('function (register_ip) { return register_ip.text; }'),
                    ],
                ])
            ],
            [
                'class' => \yii\grid\ActionColumn::className(),
                'header' => 'Действия',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        $customurl=Yii::$app->getUrlManager()->createUrl(['/admin/delete','id' => $model['id']]);
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-trash"></span>', $customurl,
                                                ['title' => Yii::t('yii', 'Деактивировать'), 'data-pjax' => '0', 
                                                'data-confirm' => 'Вы уверены что хотите сделать пользователя неактивным?', 
                                                'data-method' => 'post']);
                    }
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>

</div>
