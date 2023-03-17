<?php

use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;

/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $service app\models\release_control\ReleaseControlService
 * @var $model app\models\release_control\ReleaseControlSearch
 */

?>

<div class="release-control__filters-block js-release-control__filters-block">
    <div class="row">

        <?php $form = ActiveForm::begin(); ?>

        <?=
            $form->field($model, 'release_control_id', ['options' => ['class' => 'col-sm-3']])
                ->textInput([
                    'class' => 'form-control release-control__filter',
                ])
                ->label('ID');
        ?>

        <?=
        $form->field($model, 'description', ['options' => ['class' => 'col-sm-3']])
            ->textInput([
                'class' => 'form-control release-control__filter',
            ])
            ->label('Описание');
        ?>

        <?=
        $form->field($model, 'active', ['options' => ['class' => 'col-sm-3']])
            ->dropDownList(
                $service->getStatuses(),
                [
                    'class'=>'form-control release-control__filter',
                    'prompt' => 'Выбрать ...',
                ]
            )
            ->label('Активность');
        ?>

        <?=
        $form->field($model, 'updated_at', ['options' => ['class' => 'col-sm-3']])
            ->widget(DateRangePicker::classname(), [
                    'presetDropdown' => true,
                    'defaultPresetValueOptions' => [
                        'style' => 'display:none',
                    ],
                    'options' => [
                        'class' => 'form-control release-control__filter',
                    ],
                    'pluginOptions' => [
                        'timePicker'            => true,
                        'timePicker24Hour'      => true,
                        'timePickerIncrement'   => 15,
                        'opens'                 => 'left',
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
                
                            $(this).find('input').val('');
                            $(this).find('.range-value').empty();
                            
                            if(! $(this).is('input')) {
                                value=$(this).parent().find('input:hidden');
                            }
                            
                            value.val('').trigger(input);
                        }"
                        ),
                    ]
                ])
            ->label('Дата обновления');
        ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>
