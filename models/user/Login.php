<?php

namespace app\models\user;

use Yii;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use nkostadinov\user\Module;
use app\models\user\User;

/**
 * LoginForm is the model behind the login form.
 */
class Login extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = null;


    /**
     * Правила валидации
     * 
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'message' => 'Введите пожалуйста {attribute}'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Переименовывание названий
     * 
     * @return array
     */
    public function attributeLabels()
    {
        return[
            'username' => 'Почта',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить вас',
        ];
    }

    /**
     * Проверка валидности пароля
     *
     * @param string $attribute
     */
    public function validatePassword($attribute)
    {
        if ($this->hasErrors() === false) {
            $user = $this->getUser();

            if (($user === null) || ($user->password_hash === null) || ($user->validatePassword($this->password) === false)) {
                $this->addError($attribute, Yii::t(Module::I18N_CATEGORY, 'Неверный логин или пароль.'));
            }
        }
    }

    /**
     * Выполняет вход пользователя, используя предоставленные имя пользователя и пароль.
     * 
     * @return boolean
     */
    public function login()
    {
        if ($this->validate() === true) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }

        return false;
    }

    /**
     * Находит пользователя по электронной почте
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            try {
                $this->_user = call_user_func([Yii::$app->user->identityClass, 'findByEmail'],
                    ['value' => $this->username]);
            } catch (NotFoundHttpException $ex) {}
        }

        return $this->_user;
    }
}
