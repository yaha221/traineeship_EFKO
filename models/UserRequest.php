<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_request".
 * 
 * @property integer $id
 * @property integer $user_id
 * @property string $month
 * @property string $type
 * @property integer $tonnage
 * @property TimeStamp $created_ta
 * @property string $result_value
 * @property Text $result_table
 * @property Text $months_now
 * @property Text $tonnages_now
 */
class UserRequest extends ActiveRecord
{
    /**
     * @return array изменнённые атрибуты labels
     */
    public function attributeLabels()
    {
        return [
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
        $newUserRequest = new UserRequest();
        foreach ($userRequest as $key => $value) {
            $newUserRequest->$key = $value;
        }
        if ($newUserRequest->save()) {
            return;
        }
        throw new \Exception('Не удалось сохранить запрос');
    }
}