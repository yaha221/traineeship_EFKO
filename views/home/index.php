<?php
    $months = $data->months;
    $tonnages = $data->tonnages;
    $types = $data->types;
    $rated= $data->rated;
    $this->title = 'Калькулятор стоимости поставки сырья';
    use Tlr\Tables\Elements\Table;
?>
        <div class="row mt-5 p-5">
            <h1>Калькулятор стоимости поставки сырья</h1>
            <form action="index.php" method="POST">
                <div class="mb-3">
                <label for="month" class="form-label">Месяц</label>
                    <select class="form-select form-select-lg " name="month" aria-label="Default select example">
                        <option selected disabled>Выберети месяц</option>
                        <?php foreach ($months as $key => $value): ?>
                            <option value="<?= $key ?>"><?= $value ?></option>
                        <?php endforeach ?>
                </select>
            </div>
            <div class="mb-3">
            <label for="tonnag" class="form-label">Тоннаж</label>
                <select class="form-select form-select-lg" name="tonnag" aria-label="Default select example">
                        <option selected disabled>Тоннаж</option>
                        <?php foreach ($tonnages as $key => $value): ?>
                            <option value="<?= $key ?>"><?= $value ?></option>
                        <?php endforeach ?>
                </select>
            </div>
            <div class="mb-3">
            <label for="type" class="form-label">Сырьё</label>
                <select class="form-select form-select-lg" name="type" aria-label="Default select example">
                        <option selected disabled>Тип сырья</option>
                        <?php foreach ($types as $key => $value): ?>
                            <option value="<?= $key ?>"><?= $value ?></option>
                        <?php endforeach ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Рассчитать</button>
        </form>
        <?php foreach ($types as $keyType => $type): ?>
            <p>        
            <h3><?= $type ?></h3>
            <?= $table = new Table;
            $table->class('table table-bordered table-striped');
            $row = $table->header()->row();
            $row->cell('Месяц');
            foreach ($months as $month){
                 $row->cell($month); 
            }
            foreach ($tonnages as $keyTonnage => $tonnage){
                $row = $table->body()->row(); 
                $row->cell($tonnage);
                    for($i = 0; $i < 6; $i++){
                        $row->cell($rated[$keyType][$keyTonnage][$i]);
                    }
            } ?>
            <?= $table->render() ?>
        </p>
        <?php endforeach ?>
        <?php if( isset($_POST) && count($_POST) === 3): ?>
            <p>Выбранный месяц: <?= $monthPost = $form->monthPost ?></p>
            <p>Выбранный тоннаж: <?= $tonnagePost = $form->tonnagePost?></p>
            <p>Выбранное сырьё: <?= $typePost = $form->typePost?></p>
            <p>Рассчитанные данные: <?= $tonnagePost * $rated[$typePost][$tonnagePost][$monthPost] ?></p>
        <?php else: ?>
            <p>Введите данные</p>
        <?php endif ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>