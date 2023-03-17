<?php

namespace app\models\admin;

use Yii;
use yii\db\ActiveRecord;

class Assigment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_assignment}}';
    }

    /**
     * @return array правила валидации
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id',], 'required', 'message' => 'Выберите {attribute}',],
            [['item_name', 'user_id',], 'safe',],
        ];
    }

    /**
     * @return array изменнённые атрибуты labels
     */
    public function attributeLabels()
    {
        return [
            'item_name' => 'Роль',
            'user_id' => 'Пользователь',
        ];
    }

    /**
     * Получение всех пользователей и их ролей
     * 
     * @return array все назначенные роли
     */
    public function getUserAssigs()
    {
        return Assigment::find()->select(['item_name','user_id'])->all();
    }

    /**
     * Добавляет пользователю новую роль
     * 
     * @param string $itemName
     * @param integer $userId
     */
    public function appointUserAssig($itemName, $userId)
    {
        $auth = Yii::$app->getAuthManager();
        $role = $auth->getRole($itemName);
        
        if ($auth->getAssignment($itemName, $userId) === null) {
            $auth->assign($role, $userId);
        }

        return;
    }

    /**
     * Удаляет у пользователя роль
     * 
     * @param string $itemName
     * @param integer $userId
     */
    public function takeoffUserAssig($itemName, $userId)
    {
        $auth = Yii::$app->getAuthManager();
        if ($itemName === 'admin') {
            $role = $auth->getRole('admin');
            $auth->revoke($role,$userId);
            return;
        }

        if ($itemName === 'user') {
            $role = $auth->getRole('user');
            $auth->revoke($role,$userId);
            return;
        }
    }
}