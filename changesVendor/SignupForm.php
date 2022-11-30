<?php

namespace nkostadinov\user\models\forms;

use nkostadinov\user\models\User;
use nkostadinov\user\Module;
use nkostadinov\user\validators\PasswordStrengthValidator;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $name;
    public $email;
    public $password;
    public $passwordAgain;

    /**
     * @inheritdoc
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

    public function attributeLabels()
    {
        return[
            'username' => 'Имя',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'passwordAgain' => 'Повторите пароль',
        ];
    }

    public function uniqueEmail($attribute)
    {
        $user = call_user_func([Yii::$app->user->identityClass, 'findByEmail'],
            ['email' => $this->$attribute]);
        if ($user && $user->password_hash) {
            $this->addError($attribute, Yii::t(Module::I18N_CATEGORY, 'Эта электронная почта уже используется.'));
        }
    }

    public function uniquePassword($attribute)
    {
        if($this->password !== $this->passwordAgain) {
            $this->addError($attribute, Yii::t(Module::I18N_CATEGORY, 'Пароли не совпадают'));
        }
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        Yii::info("User is trying to register", __CLASS__);
        if ($this->validate()) {
            Yii::info("User [$this->email] passed the registration validation", __CLASS__);
            $user = call_user_func([Yii::$app->user->identityClass, 'findByEmail'],
                ['email' => $this->email]);
            if (!$user) {
                /** @var User $user */
                $user = Yii::createObject([
                    'class' => Yii::$app->user->identityClass,
                ]);
                //assign all safe attributes from the form input to the user model
                $user->setAttributes($this->getAttributes());
            }
            $user->setPassword($this->password);
            /*$auth = Yii::$app->authManager;
            $newUser = $auth->getRole('user');
            $auth->assign($newUser,$user->id);*/
            return Yii::$app->user->register($user) ? $user : false;
        }

        return false;
    }
}
