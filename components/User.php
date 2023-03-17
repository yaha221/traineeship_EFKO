<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 25.03.2015
 * Time: 13:56 ч.
 */

namespace app\components;

use Yii;
use yii\db\ActiveQueryInterface;
use yii\di\Instance;
use yii\web\User as BaseUser;
use nkostadinov\user\helpers\Event;
use nkostadinov\user\interfaces\IUserNotificator;
use nkostadinov\user\models\User as UserModel;
use nkostadinov\user\validators\PasswordStrengthValidator;
use app\behaviors\LastLoginBehavior;

class User extends BaseUser
{    
    const EVENT_BEFORE_REGISTER = 'nkostadinov.user.beforeRegister';
    const EVENT_AFTER_REGISTER = 'nkostadinov.user.afterRegister';
    const EVENT_BEFORE_OAUTH = 'nkostadinov.user.beforeOAuth';
    const EVENT_AFTER_OAUTH = 'nkostadinov.user.afterOAuth';

    public $loginForm = 'nkostadinov\user\models\forms\LoginForm';
    public $registerForm = 'nkostadinov\user\models\forms\SignupForm';

    public $enableConfirmation = true;
    public $allowUncofirmedLogin = false;
    public $requireUsername = false;

    public $identityClass = 'nkostadinov\user\User';
    public $tokenClass = 'nkostadinov\user\models\Token';
    public $enableAutoLogin = true;
    public $loginUrl = ['user/security/login'];
    public $minPasswordLength = 6;
    public $passwordStrengthConfig = ['preset' => PasswordStrengthValidator::NORMAL];
    public $adminRules = [
        [
            'allow' => true,
            'roles' => ['@']
        ]
    ];

    private $_notificator;

    public function behaviors()
    {
        return [
            'last_login' => LastLoginBehavior::className(),
        ];
    }

    /**
     * @param array $params
     * @return ActiveQueryInterface
     */
    public function listUsers($params = [])
    {
        return call_user_func([ $this->identityClass, 'find'])
            ->andFilterWhere($params);
    }

    /**
     * @return IUserNotificator
     */
    public function getNotificator()
    {
        if(isset($this->_notificator) === false) {
            $this->_notificator = Yii::createObject($this->components['notificator']);
            Instance::ensure($this->_notificator, 'nkostadinov\user\interfaces\IUserNotificator');
        }
        return $this->_notificator;
    }

    /**
     * Регистрация пользователя в системе при валидных данных
     *
     * @param UserModel $model
     * @return bool
     */
    public function register(UserModel $model)
    {

        $event = Event::createUserEvent($model);
        $this->trigger(self::EVENT_BEFORE_REGISTER, $event);

        if ($model->save() === true) {
            $this->trigger(self::EVENT_AFTER_REGISTER, $event);
            return true;
        }

        return false;
    }
}
