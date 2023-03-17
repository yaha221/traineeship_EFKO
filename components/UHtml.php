<?php
namespace app\components;

use rmrevin\yii\fontawesome\FA;

/**
 * @link http://www.efko.ru/
 * @copyright Copyright (c) АО "Управляющая компания ЭФКО" (26.10.15 13:09)
 * @author веб - программист УИТ, Кулинич Александр a.kulinich@uk.efko.ru
 */

class UHtml extends \app\modules\users\components\UsersHtml {

    /**
     * Заглушка для FA::icon
     * @param string $fa_icon_name название иконки
     * @param array $options html атрибуты к тегу i
     * @return \rmrevin\yii\fontawesome\component\Icon
     */
    public static function icon($fa_icon_name, $options = []){
        return (string)FA::icon($fa_icon_name, $options);
    }

    /**
     * FontAwesome Иконка + текст и между ними отступ в пикселах
     * @param string $fa_icon_name название иконки
     * @param string $label текст
     * @param int $offset отступ от иконки в пикселах
     * @param array $options html атрибуты к тегу i
     * @return string
     */
    public static function iconLabel($fa_icon_name, $label, $offset = 5, $options = []){
        return FA::icon($fa_icon_name, array_merge($options, ['style' => 'margin-right:'.$offset.'px'])).$label;
    }

    /**
     * Втавка аббревиатуры
     * @param string $short сокращение
     * @param string $full определение
     * @param array $options дополнительные атрибуты
     * @return string
     */
    public static function abbr($short, $full, $initialism = false, $options = []){
        $req = $initialism ? ['class' => 'initialism' , 'title' => $full]
            : ['title' => $full];

        if (is_array($options) && count($options) > 0){
            $options = array_merge($req, $options);
        } else {
            $options = $req;
        }
        return self::tag('abbr', $short, $options);
    }
}