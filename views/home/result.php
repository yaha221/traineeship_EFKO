<?php
    use Tlr\Tables\Elements\Table;
?>
    <p>Начальная стоимость: <?= $data->rated[$calculatedForm->type][$calculatedForm->tonnage][$calculatedForm->month] ?></p>
            <p>
            <h3><?= $data->types[$calculatedForm->type] ?></h3>
            <?= $table = new Table;
            $table->class('table table-bordered table-striped');
            $row = $table->header()->row();
            $row->cell('Месяц');
            foreach ($data->months as $monthItem) {
                $row->cell($monthItem);
            }
            foreach ($data->tonnages as $keyTonnage => $tonnageItem) {
                $row = $table->body()->row();
                $row->cell($tonnageItem);
                    foreach($data->months as $keyMonth => $month) {
                        $row->cell($data->rated[$calculatedForm->type][$keyTonnage][$keyMonth]);
                    }
            } ?>
            <?= $table->render() ?>
        </p>