<?php

namespace app\models;

use nkostadinov\user\models\User;
use Yii;


class MyUser extends User
{
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Электронная почта',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'confirmed_on' => 'Дата потдверждения',
            'updated_at' => 'Последнее посещение',
            'register_ip' => 'Ip регистрации',
        ];
    }
}
