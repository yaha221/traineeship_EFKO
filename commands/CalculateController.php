<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Data;

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

    /**
     * Переопределение встроенной функции options 
     * @param $actionID - the action id of the current request
     * 
     * @return string[]
     */
    public function options($actionID)
    {
        return[
            'month',
            'type',
            'tonnage'
        ];
    }

    public function actionIndex()
    {
        $data = new Data;
        $typeKey=array_search($this->type,$data->types);
        $tonnageKey=array_search($this->tonnage,$data->tonnages);
        $monthKey=array_search($this->month,$data->months);
        if(!$this->isAllOptionsInsert() || !$this->isAllItemFound($monthKey, $typeKey, $tonnageKey)) {
            return ExitCode::IOERR;
        }
        echo "\033[36m Калькулятор стоимости сырья" . PHP_EOL;
        echo "\033[1;33m Введенные парамерты: " . PHP_EOL;
        echo "\033[1;33m Месяц - " . $this->month . PHP_EOL;
        echo "\033[1;33m Тип - " . $this->type . PHP_EOL;
        echo "\033[1;33m Тоннаж - " . $this->tonnage . PHP_EOL;
        echo "\033[32m Результат - " . $data->rated[$typeKey][$tonnageKey][$monthKey] . PHP_EOL;
        $this->renderTable($data, $typeKey);
        return ExitCode::OK;
    }

    /**
     * Проверка на соответствие прайсу
     * @param $monthKey - ключ месяца
     * @param $typeKey - ключ типа
     * @param $tonnageKey - ключ тоннажа
     * 
     * @return true|false
     */
    private function isAllItemFound($monthKey, $typeKey, $tonnageKey)
    {
        if (!$monthKey && $monthKey !== 0) {
            echo "\033[31m Выполнение команды завершено с ошибкой" . PHP_EOL;
            echo "\033[31m Не найден прайс для --month = " . $this->month . PHP_EOL;
            echo "\033[31m Проверьте корректность введённых данных" . PHP_EOL;
            return false;
        }
        if (!$typeKey) {
            echo "\033[31m Выполнение команды завершено с ошибкой" . PHP_EOL;
            echo "\033[31m Не найден прайс для --type = " . $this->type . PHP_EOL;
            echo "\033[31m Проверьте корректность введённых данных" . PHP_EOL;
            return false;
        }
        if (!$tonnageKey) {
            echo "\033[31m Выполнение команды завершено с ошибкой" . PHP_EOL;
            echo "\033[31m Не найден прайс для --tonnage = " . $this->tonnage . PHP_EOL;
            echo "\033[31m Проверьте корректность введённых данных" . PHP_EOL;
            return false;
        }
        return true;
    }
    
    /**
     * Проверка на ввод всех опций(значений)
     * 
     * @return true|false
     */
    private function isAllOptionsInsert()
    {
        if ($this->month === null) {
            echo "\033[31m Выполнение команды завершено с ошибкой" . PHP_EOL;
            echo "\033[31m Необходимо ввести месяц" . PHP_EOL;
            return false;
        }
        if ($this->type === null) {
            echo "\033[31m Выполнение команды завершено с ошибкой" . PHP_EOL;
            echo "\033[31m Необходимо ввести тип" . PHP_EOL;
            return false;
        }
        if ($this->tonnage === null) {
            echo "\033[31m Выполнение команды завершено с ошибкой" . PHP_EOL;
            echo "\033[31m Необходимо ввести тоннаж" . PHP_EOL;
            return false;
        }
        return true;
    }

    /**
     * Отрисовка таблицы в консоли
     * @param $data - прайс для отрисовки
     * @param $typeKey - тип по которому будет отрисовываться таблица
     * 
     * @return null
     */
    private function renderTable($data, $typeKey)
    {
        echo "\033[32m ╔═════╦════════╦═════════╦════════╦══════════╦═════════╦════════╗" . PHP_EOL;
        echo "\033[32m ║ м\т ║ Январь ║ Февраль ║ Август ║ Сентябрь ║ Октябрь ║ Ноябрь ║" . PHP_EOL;
        foreach($data->tonnages as $keyTonnage => $tonnageItem) {
            echo "\033[32m ╠═════╬════════╬═════════╬════════╬══════════╬═════════╬════════╣" . PHP_EOL;
            if ($keyTonnage !== 100) {
                echo "\033[32m ║ " . $tonnageItem . "  ║"; 
            }
            if ($keyTonnage === 100) {
                echo "\033[32m ║ " . $tonnageItem . " ║"; 
            }
            for ($i=0; $i < 6; $i++) { 
                if ($i !== 2 && $i !== 5) {
                    echo "  " . $data->rated[$typeKey][$keyTonnage][$i] . "   ║ ";
                }
                if ($i === 2 || $i === 5) {
                    echo "  " . $data->rated[$typeKey][$keyTonnage][$i] . "  ║  ";
                }
            }
            echo PHP_EOL;
        }
        echo "\033[32m ╚═════╩════════╩═════════╩════════╩══════════╩═════════╩════════╝" . PHP_EOL;
    }
}
