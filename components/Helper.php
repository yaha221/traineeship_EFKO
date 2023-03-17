<?php
/*
 * Created by r.solyanik 03.07.2018 14:24
 */

namespace app\components;


use Yii;
use yii\base\ErrorException;
use yii\base\Model;
use yii\db\Exception;

class Helper
{
    /*
     * returns text description of models errors
     * @param Model $model
     * @return string
     */
    public static function getErrorsString(Model $model)
    {
        $errors = array_map(function ($el) {
            return $el[0];
        }, $model->getErrors());
        return implode(', ', $errors);
    }

    /*
     * return formatted dt from string dt
     * @param $str
     * @param string $format
     * @return string|null - DateTime or null if dt incorrect
     */
    public static function dateFormat($str, $format = 'Y-m-d H:i:s')
    {
        if (!$str) {
            return null;
        }
        try {
            $dt = new \DateTime($str);
            if($dt->format('Y') < 1900) {
                throw new Exception('invalid date');
            }
            return $dt->format($format);
        } catch (\Exception $ex) {

        }

        return null;
    }

    /*
     * Сглаживает многомерный массив
     * @param $array
     *
     * @return array
     */
    public static function arrayFlatten($array) {
        $return = array();
        foreach ($array as $key => $value) {
            if (is_array($value)){
                $return = array_merge($return, static::arrayFlatten($value));
            } else {
                $return[$key] = $value;
            }
        }

        return $return;
    }

    /*
     *
     * Список часов в суктах формата - H:i:s с шагом в час
     *
     * @return array
     * @throws \Exception
     */
    public static function hoursInDayList ()
    {

        $hoursInDayList = [];
        $startIterationTime = new \DateTime('23:00:00');
        $i = 1;
        do {
            $hoursInDayList[] = $startIterationTime->add(new \DateInterval('PT1H'))->format('H:i:s');
            $i++;
        } while ($i <= 24);

        return $hoursInDayList;
    }

    /*
     * Проверяет активность
     * режима продакшн
     * 
     * @return bool
     */
    public static function isProduction(): bool
    {
        return (! (defined('YII_ENV') && YII_ENV == 'dev'));
    }

    public static function numberActiveColumnsGridView(array $columns): int
    {
        return count(self::getActiveColumnsGridView($columns));
    }

    public static function getActiveColumnsGridView(array $columns): array
    {
        $filteredColumns = array_filter($columns, function($column) {
            if (! isset($column['visible'])) {
                return true;
            }
            if ($column['visible'] === true) {
                return true;
            }

            return false;
        });

        return $filteredColumns;
    }
}