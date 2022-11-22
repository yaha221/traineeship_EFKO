<?php
    $this->title = 'Калькулятор стоимости поставки сырья';
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

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
        
        <?= $form->field($calculatedForm, 'month')->dropDownList($months,['prompt' => 'Выберите месяц',])->label('Месяц') ?>
        
        <?= $form->field($calculatedForm, 'tonnage')->dropDownList($tonnages,['prompt' => 'Выберите тоннаж',])->label('Тоннаж') ?>
        
        <?= $form->field($calculatedForm, 'type')->dropDownList($types,['prompt' => 'Выберите тип',])->label('Тип') ?>
        
        <div class="form-group">
            <?= Html::submitButton('Расчитать', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="row p-5">
    <div id="feedback"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>