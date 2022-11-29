<?php

namespace app\models;

use yii\db\Query;
use Yii;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "auth_assigment".
 * 
 * @property string $item_name
 * @property string $user_id
 * @property TimeStamp $created_at
 */
class AssigmentUser extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_assignment}}';
    }

    public function rules()
    {
        return [
            [['item_name', 'user_id',], 'required', 'message' => 'Выберите {attribute}',],
            
            [['item_name', 'user_id',], 'safe',],
        ];
    }

    public function attributeLabels()
    {
        return [
            'item_name' => 'Роль',
            'user_id' => 'Пользователь',
        ];
    }

    public function createUserAssig ($item_name,$user_id)
    {
        $auth = Yii::$app->getAuthManager();
        $role = $auth->getRole('user');
        $auth->revoke($role,$user_id);
        $role = $auth->getRole('admin');
        $auth->revoke($role,$user_id);
        $role = $auth->getRole($item_name);
        $auth->assign($role, $user_id);
    }
}