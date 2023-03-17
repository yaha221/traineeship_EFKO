<?php

use yii\web\JsExpression;
use kartik\daterange\DateRangePicker;
use kartik\grid\GridView;
use kartik\switchinput\SwitchInput;

/**
 * @var $this \yii\web\View
 * @var $service \app\models\release_control\ReleaseControlService
 * @var $searchModel \app\models\release_control\ReleaseControlSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $repository \app\models\release_control\ReleaseControlRepository
 */

echo  GridView::widget([
    'id' => 'js-grid-release-control',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'filterPosition' => GridView::FILTER_POS_FOOTER,
    'filterSelector' => '.release-control__filter',
    'pjax'          => true,
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjaxSettings'  => [
        'options' => [
            'enableReplaceState' => true
        ],
    ],
    'condensed'     => true,
    'striped'       => true,
    'responsiveWrap' => true,
    'beforeRow' => function ($model) use ($repository) {
        $repository->setEntity($model);
    },
    'layout' => '{items} {pager}',
    'options' => ['class' => 'grid-list-view'],
    'emptyText' => 'Не найдено',
    'columns' => [
        [
            'attribute' => 'release_control_id',
            'format' => 'raw',
            'headerOptions' => ['style' => 'width: 50px;'],
            'value' => function () use ($service) {
                return $service->printAttribute('release_control_id');
            },
        ],
        [
            'attribute' => 'description',
            'format' => 'raw',
            'headerOptions' => ['style' => 'width: 50px;'],
            'value' => function () use ($service) {
                return $service->printAttribute('description');
            },
        ],
        [
            'attribute' => 'active',
            'format' => 'raw',
            'headerOptions' => ['style' => 'width: 50px;'],
            'value' => function () use ($service) {
                return SwitchInput::widget([
                    'name' => 'active',
                    'model' => $service,
                    'attribute' => 'active',
                    'value' => $service->printAttribute('active'),
                    'pluginOptions' => [
                        'onText' => Yii::t('app', 'Да'),
                        'offText' => Yii::t('app', 'Нет'),
                        'labelText' => 'Активировать',
                    ],
                    'pluginEvents' => [
                        'switchChange.bootstrapSwitch' => 'function(event, value) {
                                 $.get(
                                     "/release-control/change",
                                     {"id": '. $service->printAttribute('release_control_id') .', "value": value}
                                 )
                            }',
                    ],
                    'labelOptions' => ['style' => 'font-size: 10px'],
                    'disabled' => $service->printAttribute('locked') == 1,
                ]);
            },
        ],
        [
            'attribute' => 'updated_at',
            'format' => 'raw',
            'filter' => DateRangePicker::widget([
                'attribute' => 'updated_at',
                'language' => 'RU',
                'model'         => $searchModel,
                'presetDropdown' => true,
                'defaultPresetValueOptions' => [
                    'style' => 'display:none',
                ],
                'pluginOptions' => [
                    'timePicker'            => true,
                    'timePicker24Hour'      => true,
                    'timePickerIncrement'   => 15,
                    'opens'                 => 'center',
                    'locale'                => [
                        'language' => 'RU',
                        'format'   => 'DD.MM.YYYY H:mm',
                    ],
                    'initRangeExpr' => true,
                ],
                'pluginEvents' => [
                    'cancel.daterangepicker' => new JsExpression("function(ev, picker) {
                        let input=$.Event('keydown');
                        input.keyCode=13;
                            let value=$(this);
                            
                            if(! $(this).is('input')) {
                                value=$(this).parent().find('input:hidden');
                            }
                            
                        value.val('').trigger(input);
                        }"),
                ]
            ]),
            'headerOptions' => ['style' => 'width: 50px;'],
            'value' => function () use ($service) {
                return $service->printUpdatedAtFormat();
            },
        ],
    ],
]);

?>