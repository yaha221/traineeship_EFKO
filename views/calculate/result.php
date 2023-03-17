<?php
    use Tlr\Tables\Elements\Table;
    use yii\helpers\ArrayHelper;
?>
<p>Начальная стоимость: <?= $costValue ?></p>
<p>Месяц: <?= $monthData ?></p>
<p>Тоннаж: <?= $tonnageData ?></p>
<p>Тип: <?= $type ?></p>
<p>
    <h3><?= $type ?></h3>
    <?= $table = new Table;
    $table->class('table table-bordered table-striped');
    $row = $table->header()->row();
    $row->cell('Месяц');
    foreach (ArrayHelper::map($repository->findMonths(), 'id', 'name') as $monthItem) {
        $row->cell($monthItem);
    }
    foreach (ArrayHelper::map($repository->findTonnages(), 'id', 'value')  as $keyTonnage => $tonnageItem) {
        $row = $table->body()->row();
        $row->cell($tonnageItem);
            foreach(ArrayHelper::map($repository->findMonths(), 'id', 'name') as $keyMonth => $month) {
                $row->cell($costTable[$typeId][$keyTonnage][$keyMonth]);
            }
    } ?>
    <?= $table->render() ?>
</p>