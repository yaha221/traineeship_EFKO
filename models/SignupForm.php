<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class SignupForm extends Model
{
    public $username;
    public $password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'message' => 'Пожалуйста введите {attribute}',],
            [['username', 'password'],'safe'],
            [['username'],'unique', 'targetClass' => 'app\models\User', 'targetAttribute' => 'username', 'message' => ' {attribute} уже используется'],
        ];
    }

    public function attributeLabels()
    {
        return[
            'username' => 'Логин',
            'password' => 'Пароль',
        ];
    }
}