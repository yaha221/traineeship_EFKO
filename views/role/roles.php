<?php
    $this->title = 'Обновление ролей';
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use Tlr\Tables\Elements\Table;
?>

<div class="row mt-5 p-5">
        <h1>Обновление ролей</h1>
        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
        ]); ?>
        
        <?= $form->field($assigment, 'user_id')->dropDownList($users,['prompt' => 'Выберите пользователя',])?>
        
        <?= $form->field($assigment, 'item_name')->dropDownList($roles,['prompt' => 'Выберите роль',])?>
        
        <div class="form-group">
            <?= Html::submitButton('Назначить', [
                'class' => 'btn btn-primary',
                'formaction' => Url::to(['appoint']),
            ]) ?>
            <?= Html::submitButton('Снять', [
                'class' => 'btn btn-primary',
                'formaction' => Url::to(['takeoff']),
            ]) ?>
        </div>
        <?php ActiveForm::end(); ?>
</div>
<div class="row p-5">
    <p>
    <?= $table = new Table;
    $table->class('table table-bordered table-striped');
    $row = $table->header()->row();
    $row->cell('Пользователь');
    $row->cell('Роль');
    foreach ($assigmentsTable as $key => $user) {
        $row = $table->body()->row();
        $row->cell($user);
        $row->cell($assigmentsItem[$key]);
    } 
    ?>
    <?= $table->render() ?>
    </p>
</div>