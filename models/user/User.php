<?php

namespace app\models\user;

use nkostadinov\user\models\User as BaseUser;

class User extends BaseUser
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }
    
    /**
     * @return array переименовывание названий
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'username' => 'Пользователь',
            'email' => 'Электронная почта',
            'status' => 'Статус',
            'created_at' => 'Дата регистрации',
            'last_login' => 'Дата входа',
            'last_login_ip' => 'Ip входа',
            'register_ip' => 'Ip регистрации',
        ];
    }

    /**
     * @return array правила валидации
     */
    public function rules()
    {
        $rules = [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['email', 'email'],
            ['email', 'required', 'message' => 'Введите пожалуйста {attribute}'],

            ['username', 'safe'],
        ];

        if(\Yii::$app->user->requireUsername === true) {
            $rules[] = ['username', 'required', 'message' => 'Введите пожалуйста {attribute}'];
        }

        return $rules;
    }

    /**
     * Установка связи многие к одному с запросами пользователя
     * 
     * @return User
     */
    public function getUserRequests()
    {
        return $this->hasMany(UserRequest::class, ['user_id' => 'id']);
    }
}