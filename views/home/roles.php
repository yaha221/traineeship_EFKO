<?php
    $this->title = 'Обновление ролей';
    use yii\helpers\Html;
    use yii\bootstrap\Alert;
    use yii\widgets\ActiveForm;

?>

<div class="row mt-5 p-5">
        <h1>Обновление ролей</h1>
        <?php $form = ActiveForm::begin([
            'id' => 'role-form',
            'enableAjaxValidation' => true,
            'options' => [
                'onsubmit' => 'return false',
             ],
        ]); ?>
        
        <?= $form->field($assigment, 'user_id')->dropDownList($users,['prompt' => 'Выберите пользователя',])?>
        
        <?= $form->field($assigment, 'item_name')->dropDownList($roles,['prompt' => 'Выберите роль',])?>
        
        <div class="form-group">
            <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="row p-5">
    <div id="role-feedback"></div>
    </div>