<?php

use Tlr\Tables\Elements\Table;
use Jenssegers\Date\Date;

    $this->title = 'Запрос';
    if (Yii::$app->user->can('admin')) {
        $this->title = 'Запрос ' . $model->user->username;
    };
?>
<?php
if (Yii::$app->user->can('admin')) {
    echo '<p>Пользователь: <b>' . $model->user->username . '</b></p>';
}
?>
<p>Дата создания: <b><?= Date::parse($model->created_at)->format('d.m.Y H:i:s') ?></b></p>
<p>Месяц: <b><?= $model->month ?></b></p>
<p>Тоннаж: <b><?= $model->tonnage ?></b></p>
<p>Результат: <b><?= $model->result_value ?></b></p>
<p>
    <h3><?= $model->type ?></h3>
    <?= 
    $table = new Table;
    $table->class('table table-bordered table-striped');

    $row = $table->header()->row();
    $row->cell('Месяц');
    
    $costTable =unserialize($model->result_table);

    foreach (unserialize($model->months_now) as $monthItem) {
        $row->cell($monthItem);
    }

    foreach (unserialize($model->tonnages_now) as $keyTonnage => $tonnageItem) {
        $row = $table->body()->row();
        $row->cell($tonnageItem);

            foreach(unserialize($model->months_now) as $keyMonth => $month) {
                $row->cell($costTable[$keyTonnage][$keyMonth]);
            }
    }
    ?>
    <?= $table->render() ?>
</p>