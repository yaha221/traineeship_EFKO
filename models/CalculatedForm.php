<?php

namespace app\models;

use yii\base\Model;

/**
 * CalculatedForm является моделью расчётной формы
 */
class CalculatedForm extends Model
{
    public $month;
    public $tonnage;
    public $type;


    /**
     * @return array правила валидации
     */
    public function rules()
    {
        return[
            [['type','tonnage','month',],'required','message' => 'Введите в {attribute} что-нибудь',],
            [['type','tonnage','month',],'safe',],
        ];
    }

    /**
     * @return array изменнённые атрибуты labels
     */
    public function attributeLabels()
    {
        return[
            'type' => "тип",
            'tonnage' => "тоннаж",
            'month' => "месяц"
        ];
    }
}