<?php

namespace app\models;

use yii\base\Model;

/**
 * CalculatedForm является моделью расчётной формы
 * 
 * @property int $month выбранный месяц
 * @property int $tonnage выбранный тоннаж
 * @property int $type выбранный тип
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
            // type, tonnage и month являются обязательными полями
            [['type', 'tonnage', 'month',], 'required', 'message' => 'Введите в {attribute} что-нибудь',],
            // type, tonnage и month должны быть безопасными
            [['type', 'tonnage', 'month',], 'safe',],
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
            'month' => "месяц",
        ];
    }
}