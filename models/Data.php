<?php

namespace app\models;

class Data extends \yii\base\BaseObject 
{
    public $months = [ 1 => 'Январь', 2 => 'Февраль', 3 => 'Август', 4 => 'Сентябрь', 5 => 'Октябрь', 6 => 'Ноябрь',];
    public $types = [1 => 'Соя', 2 => 'Жмых', 3 => 'Шрот', ];
    public $tonnages = [25 => 25, 50 => 50, 75 => 75, 100 => 100,];
    public $rated = [1 => [
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
}