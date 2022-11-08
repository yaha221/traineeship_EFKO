<?php
    $this->title = 'Калькулятор стоимости поставки сырья';
    use Tlr\Tables\Elements\Table;
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

?>
        <div class="row mt-5 p-5">
            <h1>Калькулятор стоимости поставки сырья</h1>
            <?php $form = ActiveForm::begin([
                'id' => 'calculated-form',
            ]); ?>
            
            <?= $form->field($calculatedForm, 'month')->dropDownList($data->months,['prompt' => 'Выберите месяц',])->label('Месяц') ?>
            
            <?= $form->field($calculatedForm, 'tonnage')->dropDownList($data->tonnages,['prompt' => 'Выберите тоннаж',])->label('Тоннаж') ?>
            
            <?= $form->field($calculatedForm, 'type')->dropDownList($data->types,['prompt' => 'Выберите тип',])->label('Тип') ?>
            
            <div class="form-group">
                <?= Html::submitButton('Расчитать', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        <div id="result-box"></div>

        <?php foreach ($data->types as $keyType => $typeItem): ?>
            <p>        
            <h3><?= $typeItem ?></h3>
            <?= $table = new Table;
            $table->class('table table-bordered table-striped');
            $row = $table->header()->row();
            $row->cell('Месяц');
            foreach ($data->months as $monthItem){
                 $row->cell($monthItem); 
            }
            foreach ($data->tonnages as $keyTonnage => $tonnageItem){
                $row = $table->body()->row(); 
                $row->cell($tonnageItem);
                    for($i = 0; $i < 6; $i++){
                        $row->cell($data->rated[$keyType][$keyTonnage][$i]);
                    }
            } ?>
            <?= $table->render() ?>
        </p>
        <?php endforeach ?>
        <p>Введите данные</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>