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
            foreach ($data->months as $monthItem){
                 $row->cell($monthItem); 
            }
            foreach ($data->tonnages as $keyTonnage => $tonnageItem){
                $row = $table->body()->row(); 
                $row->cell($tonnageItem);
                    for($i = 0; $i < 6; $i++){
                        $row->cell($data->rated[$calculatedForm->type][$keyTonnage][$i]);
                    }
            } ?>
            <?= $table->render() ?>
        </p>