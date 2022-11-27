<?php

use Tlr\Tables\Elements\Table;

    $this->title = 'Запрос';
    if (Yii::$app->user->can('admin')) {
        $this->title = 'Запрос ' . $username;
    };
?>
<?php
if (Yii::$app->user->can('admin')) {
    echo "<p>Пользователь: <b>$username </b></p>";
}
?>
<p>Дата создания: <b><?= $createdDate ?></b></p>
<p>Месяц: <b><?= $month ?></b></p>
<p>Тоннаж: <b><?= $tonnage ?></b></p>
<p>Результат: <b><?= $result ?></b></p>
<p>
    <h3><?= $type ?></h3>
    <?= $table = new Table;
    $table->class('table table-bordered table-striped');
    $row = $table->header()->row();
    $row->cell('Месяц');
    foreach ($months as $monthItem) {
        $row->cell($monthItem);
    }
    foreach ($tonnages as $keyTonnage => $tonnageItem) {
        $row = $table->body()->row();
        $row->cell($tonnageItem);
            foreach($months as $keyMonth => $month) {
                $row->cell($costTable[$keyTonnage][$keyMonth]);
            }
    } ?>
    <?= $table->render() ?>
</p>