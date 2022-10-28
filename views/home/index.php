<?php
    $months = [ 1 => 'Январь', 2 => 'Февраль', 3 => 'Август', 4 => 'Сентябрь', 5 => 'Октябрь', 6 => 'Ноябрь',];
    $tonnages = [25 => 25, 50 => 50, 75 => 75, 100 => 100,];
    $types = [1 => 'Соя', 2 => 'Жмых', 3 => 'Шрот', ];
    $rated= [1 => [
        25=>[137, 125, 124, 122, 137, 121,],
        50=>[147, 145, 145, 143, 119, 118,],
        75=>[112, 136, 136, 112, 141, 137,],
        100=>[122, 138, 138, 117, 117, 142,],
    ],
    2 => [
        25=>[121, 137, 124, 137, 122, 125,],
        50=>[118, 121, 145, 147, 143, 145,],
        75=>[137, 124, 136, 143, 112, 136,],
        100=>[142, 131, 138, 112, 117, 117,],
    ],
    3 => [
        25=>[125, 121, 137, 126, 124, 128,],
        50=>[145, 118, 119, 121, 122, 147,],
        75=>[136, 137, 141, 137, 131, 143,],
        100=>[ 138, 142, 117, 124, 147, 112,],
    ]];
    $this->title = 'Калькулятор стоимости поставки сырья';
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
            <table class="table table-bordered table-striped ">
                <thead >
                    <tr>
                        <th scope="col">
                            <table>
                                <tr><th>Месяц</th></tr>
                                <tr><th>Тоннаж</th></tr>
                            </table>
                        </th>
                        <?php foreach ($months as $month): ?>
                            <th scope="col">
                            <table>
                                <tr><th><?= $month ?></th></tr>
                                <tr><th>_________</th></tr>
                            </table>
                            </th>
                        <?php endforeach ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php foreach ($tonnages as $keyTonnage => $tonnage): ?>
                            <th scope="row"><?= $tonnage ?></th>
                            <?php for($i = 0; $i < 6; $i++): ?>
                                    <td><?= $rated[$keyType][$keyTonnage][$i] ?></td>
                                <?php endfor ?>
                            </tr>
                        <?php endforeach ?>
                </tbody>
            </table>
        </p>
        <?php endforeach ?>
        <?php if( isset($_POST) && count($_POST) === 3): ?>
            <p>Выбранный месяц: <?= $_POST["month"] ?></p>
            <p>Выбранный тоннаж: <?= $_POST["tonnag"] ?></p>
            <p>Выбранное сырьё: <?= $_POST["type"] ?></p>
            <p>Рассчитанные данные: <?= $_POST["tonnag"] * $rated[$_POST["type"]][$_POST["tonnag"]][$_POST["month"]] ?></p>
        <?php else: ?>
            <p>Введите данные</p>
        <?php endif ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>