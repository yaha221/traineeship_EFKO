<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 20.04.2015
 * Time: 14:20 ч.
 */

namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\UserEvent;
use app\components\User;

class LastLoginBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            User::EVENT_AFTER_LOGIN => 'setLastLogin',
        ];
    }

    /**
     * Учёт последнего входа пользователя
     * 
     * @param UserEvent $event
     */
    public function setLastLogin(UserEvent $event)
    {
        $event->identity->last_login = date('Y-m-d H:i:s');
        $event->identity->last_login_ip = Yii::$app->getRequest()->isConsoleRequest ? '127.0.0.1' : Yii::$app->getRequest()->getUserIP();
        $event->identity->save();
    }
}