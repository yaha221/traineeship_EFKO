<?php
    $this->title = 'История расчётов';

    use yii\grid\GridView;
    use yii\web\JsExpression;
    use yii\widgets\Pjax;
    use kartik\select2\Select2;
    use kartik\daterange\DateRangePicker;
    use Jenssegers\Date\Date;
    use yii\helpers\ArrayHelper;

?>
    <div class="row mt-5 p-5">
    <?php Pjax::begin() ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'summary' => "Показаны запросы с {begin, number} по {end, number} из {totalCount, number}",
            'emptyText' => 'Ничего не найдено',
            'columns' => [
                'id',
                [
                    'label' => 'Пользователи',
                    'attribute' => 'user_id',
                    'value' => function($model) {
                        return $model->user->username;
                    },
                    'visible' => \Yii::$app->user->can('admin'),
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'name' => 'username',
                        'attribute' => 'user_id',
                        'data' => $repository->findUsernames(),
                        'value' => 'user_id.username',
                        'language' => 'ru',
                        'size' => 'sm',
                        'options' => [
                            'prompt' => 'Выберите пользователя',
                        ],
                        'pluginOptions' => [
                            'minimumInputLength' => 3,
                            'allowClear' => true,
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['/client/usersearch']),
                                'dataType' => 'json',
                                'data' =>  new JsExpression('function (params) { return {q:params.term}; }'),
                                'delay' => 250,
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function (model) { return model.text; }'),
                            'templateSelection' => new JsExpression('function (user_id) { return user_id.text; }'),
                        ],
                    ])
                ],
                [   
                    'label' => 'Месяц',
                    'attribute' => 'month',
                    'value' => 'month',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'data' => ArrayHelper::map($repository->findMonths(), 'name', 'name'),
                        'attribute' => 'month',
                        'size' => 'sm',
                        'options' => [
                            'placeholder' => 'Выберите значение'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ])
                ],
                [   
                    'label' => 'Тип',
                    'attribute' => 'type',
                    'value' => 'type',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'data' => ArrayHelper::map($repository->findTypes(), 'name', 'name'),
                        'attribute' => 'type',
                        'size' => 'sm',
                        'options' => [
                            'placeholder' => 'Выберите значение'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ])
                ],
                [   
                    'label' => 'Тоннаж',
                    'attribute' => 'tonnage',
                    'value' => 'tonnage',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'data' => ArrayHelper::map($repository->findTonnages(), 'value', 'value'),
                        'attribute' => 'tonnage',
                        'size' => 'sm',
                        'options' => [
                            'placeholder' => 'Выберите значение'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ])
                ],
                [
                    'label' => 'Дата запроса',
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
                    'class' => \yii\grid\ActionColumn::className(),
                    'header' => 'Таблица',
                    'visibleButtons'  => [
                        'delete' => \Yii::$app->user->can('admin'),
                    ],
                    'buttons' => [
                        'view' => function ($url, $model) {
                            $customurl=Yii::$app->getUrlManager()->createUrl(['/client/table','id' => $model['id']]); 
                            return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $customurl,
                                                    ['title' => Yii::t('yii', 'Посмотреть'), 'data-pjax' => '0']);
                        },
                        'delete' => function ($url, $model) {
                            $customurl=Yii::$app->getUrlManager()->createUrl(['/client/delete','id' => $model['id']]);
                            return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-trash"></span>', $customurl,
                                                    ['title' => Yii::t('yii', 'Удалить'), 'data-pjax' => '0', 
                                                    'data-confirm' => 'Вы уверены что хотите удалить запрос?', 
                                                    'data-method' => 'post']);
                        }
                    ],
                    'template' => '{view} {delete}',
                ],
            ],
        ]); ?>
    <?php Pjax::end() ?>
    </div>
    <div id="resultTable">

    </div>