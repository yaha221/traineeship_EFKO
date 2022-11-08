<?php

namespace app\models;

use yii\base\Model;
use Yii;

class CalculatedForm extends Model
{
    public $month;
    public $tonnage;
    public $type;

    public function rules(){
        return[
            [['type','tonnage','month',],'required','message' => 'Введите в {attribute} что-нибудь',],
            [['type','tonnage','month',],'safe',],
        ];
    }

    public function attributeLabels(){
        return[
            'type' => "тип",
            'tonnage' => "тоннаж",
            'month' => "месяц"
        ];
    }
}