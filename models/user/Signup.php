<?php

namespace app\models\user;

use Yii;
use yii\base\Model;
use nkostadinov\user\Module;
use nkostadinov\user\validators\PasswordStrengthValidator;
use app\models\user\User;

/**
 * Форма регистрации
 */
class Signup extends Model
{
    public $username;
    public $email;
    public $password;
    public $passwordAgain;

    /**
     * Правила валидации формы
     * 
     * @return array
     */
    public function rules()
    {
        $rules = [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => 'Введите пожалуйста {attribute}'],
            ['email', 'email'],
            ['email', 'uniqueEmail'],
            ['username', 'safe'],
            ['username', 'match', 'pattern' => '/^[A-zА-я-.]+$/u', 'message'=>'Только буквы'],

            ['password', 'required', 'message' => 'Введите пожалуйста {attribute}'],
            ['password', 'string', 'min' => Yii::$app->user->minPasswordLength],
            array_merge(['password', PasswordStrengthValidator::className()],
                Yii::$app->user->passwordStrengthConfig),
            ['passwordAgain', 'required', 'message' => 'Введите пожалуйста {attribute}'],
            ['passwordAgain', 'uniquePassword'],
        ];

        if(\Yii::$app->user->requireUsername === true) {
            $rules[] = ['username', 'required', 'message' => 'Введите пожалуйста {attribute}'];
            $rules[] =  ['username', 'string', 'min' => 2, 'max' => 255];
            $rules[] =  ['username', 'filter', 'filter' => 'trim'];
            $rules[] =  ['username', 'unique', 'targetClass' => 'nkostadinov\user\models\User', 'message' => 'Это имя пользователя уже используется.'];
        }

        return $rules;
    }

    /**
     * Русификация аттрибутов для формы
     * 
     * @return array
     */
    public function attributeLabels()
    {
        return[
            'username' => 'Имя',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'passwordAgain' => 'Повторите пароль',
        ];
    }

    /**
     * Проверка нахождения введённой почты в базе данных
     * 
     * @param string $attribute
     */
    public function uniqueEmail($attribute)
    {
        $user = call_user_func([Yii::$app->user->identityClass, 'findByEmail'],
            ['email' => $this->$attribute]);

        if ($user !== null && $user->password_hash !== null) {
            $this->addError($attribute, Yii::t(Module::I18N_CATEGORY, 'Эта электронная почта уже используется.'));
        }
    }

    /**
     * Проверка совпадения введённых паролей
     * 
     * @param string $attribute
     */
    public function uniquePassword($attribute)
    {
        if($this->password !== $this->passwordAgain) {
            $this->addError($attribute, Yii::t(Module::I18N_CATEGORY, 'Пароли не совпадают'));
        }
    }

    /**
     * Регистрирует пользователей в системе.
     *
     * @return User|false
     */
    public function signup()
    {
        if ($this->validate() === true) {
            $user = call_user_func([Yii::$app->user->identityClass, 'findByEmail'],
            ['email' => $this->email]);
            
            if ($user === null) {
                $user = Yii::createObject([
                    'class' => Yii::$app->user->identityClass,
                ]);
                $user->setAttributes($this->getAttributes());
                $cookies = Yii::$app->response->cookies;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'isAlert' . $user->username,
                    'value' => true,
                ]));
            }
            
            $user->setPassword($this->password);
            return Yii::$app->user->register($user) ? $user : false;
        }
        
        return false;
    }
}
