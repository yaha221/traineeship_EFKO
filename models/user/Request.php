<?php

namespace app\models\user;

use yii\db\ActiveRecord;

class Request extends ActiveRecord
{
    /**
     * @return array изменнённые атрибуты labels
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'month' => 'Месяц',
            'type' => 'Тип',
            'tonnage' => 'Тоннаж',
            'created_at' => 'Дата запроса',
            'result_value' => 'Вывод',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_request}}';
    }

    /**
     * Добавляет в историю запросов запрос пользователя
     * 
     * @throws Exception возвращает ошибку
     */
    public function createUserRequest(array $userRequest)
    {
        $newRequest = new Request();

        foreach ($userRequest as $key => $value) {
            $newRequest->$key = $value;
        }

        if ($newRequest->save() === true) {
            return;
        }

        throw new \Exception('Не удалось сохранить запрос');
    }

    /**
     * Установка связи один ко многим с пользователем
     * 
     * @return User
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}