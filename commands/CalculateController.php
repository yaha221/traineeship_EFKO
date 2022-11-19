<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use app\models\Data;
use LucidFrame\Console\ConsoleTable;

/**
 * CalculateController команда работы приложения в консоли
 * 
 * @property string $month опция выбранного месяца
 * @property string $tonnage опция выбранного тоннажа
 * @property string $type опция выбранного типа
 */

class CalculateController extends Controller
{

    public $month;
    public $type;
    public $tonnage;

    private $monthKey;
    private $tonnageKey;
    private $typeKey;
    private $keyMap = [];
    private $optionsMap = [];

    /**
     * Инициализация карты опций
     * 
     * @return string[]
     */
    public function init():void
    {
        $this->optionsMap = [
            'month' => 'Месяц',
            'tonnage' => 'Тоннаж',
            'type' => 'Тип сырья',
        ];
    }

    /**
     * Переопределение встроенной функции options 
     * @param $actionID - the action id of the current request
     * 
     * @return string[]
     */
    public function options($actionID)
    {
        return array_merge(parent::options($actionID), array_keys($this->optionsMap));
    }

    
    public function actionIndex()
    {
        $data = new Data;
        $this->typeKey=array_search(mb_convert_case(mb_strtolower($this->type, 'UTF-8'), MB_CASE_TITLE, 'UTF-8'), $data->types);
        $this->tonnageKey=array_search($this->tonnage, $data->tonnages);
        $this->monthKey=array_search(mb_convert_case(mb_strtolower($this->month, 'UTF-8'), MB_CASE_TITLE, 'UTF-8'), $data->months);
        $this->keyMap = [
            'typeKey' => '--type = ' . $this->type,
            'tonnageKey' => '--tonnage = ' . $this->tonnage,
            'monthKey' => '--month = ' . $this->month,
        ];

        try {
            $this->validate();
            $this->checkSearch($this->keyMap);
        } catch (\InvalidArgumentException $e) {
            
            $this->stdout($e->getMessage() . PHP_EOL, Console::FG_RED);
            
            return ExitCode::IOERR;
        }
        
        echo "\033[36m Калькулятор стоимости сырья" . PHP_EOL;
        echo "\033[1;33m Введенные парамерты: " . PHP_EOL;
        echo "\033[1;33m Месяц - " . $this->month . PHP_EOL;
        echo "\033[1;33m Тип - " . $this->type . PHP_EOL;
        echo "\033[1;33m Тоннаж - " . $this->tonnage . PHP_EOL;
        echo "\033[32m Результат - " . $data->rated[$this->typeKey][$this->tonnageKey][$this->monthKey] . PHP_EOL;

        $this->renderTable($data);
        return ExitCode::OK;
    }
    
    /**
     * Проверка на соответствие прайсу
     */
    private function checkSearch():void
    {
        $errorLines = [];

        foreach ($this->keyMap as $key => $value) {
            if ($this->{$key} !== false) {
                continue;
            }

            $errorLines[] = 'Не найден прайс для ' . $value;
        }

        if (empty($errorLines) === true) {
            return;
        }

        array_unshift($errorLines, 'Выполнение команды завершено с ошибкой');
    
        foreach ($errorLines as $line) {
            $this->stdout($line . PHP_EOL, Console::FG_RED);
        }

        throw new \InvalidArgumentException('Проверьте корректность введённых данных');
    }


    /**
     * Проверка на ввод всех опций(значений)
     */
    private function validate(): void
    {
        $errorLines = [];

        foreach ($this->optionsMap as $key => $value) {
            if ($this->{$key} !== null) {
                continue;
            }

            $errorLines[] = 'Необходимо ввести ' . $value;
        }

        if (empty($errorLines) === true) {
            return;
        }

        array_unshift($errorLines, 'Выполнение команды завершено с ошибкой');

        foreach($errorLines as $line) {
            $this->stdout($line . PHP_EOL, Console::FG_RED);
        }

        throw new \InvalidArgumentException();
    }

    /**
     * Отрисовка таблицы в консоли
     * @param $data - прайс для отрисовки
     * 
     * @return null
     */
    private function renderTable($data)
    {
        $table = new ConsoleTable();
        $table->addHeader('м\т');
        foreach ($data->months as $month) {
            $table->addHeader($month);
        }
        foreach ($data->tonnages as $keyTonnage => $tonnage) {
            $table->addRow()->addColumn($tonnage);
            foreach($data->months as $keyMonth => $month) {
                $table->addColumn($data->rated[$this->typeKey][$keyTonnage][$keyMonth]);
            }
        }
        $table->display();
    }
}
