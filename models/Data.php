<?php

namespace app\models;

use Tlr\Tables\Elements\Table;

class Data extends \yii\base\BaseObject 
{
    public $months = [ 
        0 => 'Январь',
        1 => 'Февраль',
        2 => 'Август',
        3 => 'Сентябрь',
        4 => 'Октябрь',
        5 => 'Ноябрь',
    ];
    public $types = [
        1 => 'Соя',
        2 => 'Жмых',
        3 => 'Шрот',
    ];
    public $tonnages = [
        25 => 25,
        50 => 50,
        75 => 75,
        100 => 100,
    ];
    public $rated = [1 => [
        25 => [ 137, 125, 124, 122, 137, 121,],
        50 => [ 147, 145, 145, 143, 119, 118,],
        75 => [ 112, 136, 136, 112, 141, 137,],
        100 => [ 122, 138, 138, 117, 117, 142,],
    ],
    2 => [
        25 => [ 121, 137, 124, 137, 122, 125,],
        50 => [ 118, 121, 145, 147, 143, 145,],
        75 => [ 137, 124, 136, 143, 112, 136,],
        100 => [ 142, 131, 138, 112, 117, 117,],
    ],
    3 => [
        25 => [ 125, 121, 137, 126, 124, 128,],
        50 => [ 145, 118, 119, 121, 122, 147,],
        75 => [ 136, 137, 141, 137, 131, 143,],
        100 => [ 138, 142, 117, 124, 147, 112,],
    ]];


    /**
     * Генерация таблицы
     * 
     * @param int $type номер типа по которому быдет строиться таблица
     * @return string возвращает сгенерированную разметку таблицы html в виде строки
     */
    public function makeTable($type){
        $table = new Table;
        $table -> class('table table-bordered table-striped');
            $row = $table->header()->row();
            $row -> cell('Месяц');
            foreach ($this -> months as $monthItem){
                 $row -> cell($monthItem); 
            }
            foreach ($this -> tonnages as $keyTonnage => $tonnageItem){
                $row = $table->body()->row(); 
                $row -> cell($tonnageItem);
                    for($i = 0; $i < 6; $i++){
                        $row -> cell($this -> rated[$type][$keyTonnage][$i]);
                    }
            }
        return $table -> render() ;
    }

    /**
     * Отображение данных, полученных из формы
     * 
     * @param int $result начальная стоимость перевозки
     * @param string $type тип перевозимого товара
     * @param string $table таблица, сгенерированная по типу
     * @return string возвращает html разметку, отпровляемую на форму
     */
    public function viewResult($result, $type, $table){
        $textResult = "<p> Начальная стоимость: $result </p>";
        $typeHeader = "<h3> $type </h3>";
        return $textResult . $typeHeader . $table;
    }
}