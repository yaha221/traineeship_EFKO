<?php
    $this->title = 'Калькулятор стоимости поставки сырья';
    use yii\helpers\Html;
    use yii\bootstrap\Alert;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
?>
<?php
    if (Yii::$app->user->isGuest === false && $isAlert === true) {
        Alert::begin([
            'options' => [
                'class' => 'alert-success',
                'id' => 'my-alert'
            ],
            'body' => "Здравствуйте, <b>" . Yii::$app->user->identity->username . "</b>, вы авторизовались в системе расчета стоимости доставки. 
            Теперь все ваши расчеты будут сохранены для последующего просмотра в <a href=\"/home/history\" class=\"alert-link\"> журнале расчетов</a>. ",
            'closeButton' => [
                'id' => 'close-alert',
            ],
        ]);
        Alert::end();
    }
?>

    <div class="row mt-5 p-5">
        <h1>Калькулятор стоимости поставки сырья</h1>
        <?php $form = ActiveForm::begin([
            'id' => 'calculated-form',
            'enableAjaxValidation' => true,
            'options' => [
                'onsubmit' => 'return false',
             ],
        ]); ?>
        
        <?= $form->field($calculatedForm, 'month')->dropDownList(ArrayHelper::map($repository->findMonths(), 'id', 'name'),['prompt' => 'Выберите месяц',])->label('Месяц') ?>
        
        <?= $form->field($calculatedForm, 'tonnage')->dropDownList(ArrayHelper::map($repository->findTonnages(), 'id', 'value'),['prompt' => 'Выберите тоннаж',])->label('Тоннаж') ?>
        
        <?= $form->field($calculatedForm, 'type')->dropDownList(ArrayHelper::map($repository->findTypes(), 'id', 'name'),['prompt' => 'Выберите тип',])->label('Тип') ?>
        
        <div class="form-group">
            <?= Html::submitButton('Расчитать', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="row p-5">
    <div id="feedback"></div>
    </div>