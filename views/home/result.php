<?php
    use Tlr\Tables\Elements\Table;
?>
<p>Начальная стоимость: <?= $costValue ?></p>
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
                $row->cell($costTable[$typeData['id']][$keyTonnage][$keyMonth]);
            }
    } ?>
    <?= $table->render() ?>
</p>