<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use nkostadinov\user\Module;
use app\models\user\User;

?>

<div class="user-form row">

    <div class="col-lg-6 col-md-6">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput() ?>
        <?= $form->field($model, 'email')->textInput() ?>
        <?= $form->field($model, 'status')->dropDownList([
            User::STATUS_ACTIVE => Yii::t(Module::I18N_CATEGORY, 'Активен'),
            User::STATUS_DELETED => Yii::t(Module::I18N_CATEGORY, 'Неактивен'),
        ]); ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t(Module::I18N_CATEGORY, 'Создать') : Yii::t(Module::I18N_CATEGORY, 'Обновить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
