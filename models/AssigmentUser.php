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

    public function getUserAssigs ()
    {
        return AssigmentUser::find()->select(['item_name','user_id'])->all();
    }

    public function appointUserAssig ($item_name, $user_id)
    {
        $auth = Yii::$app->getAuthManager();
        $role = $auth->getRole($item_name);
        if ($auth->getAssignment($item_name, $user_id) === null) {
            $auth->assign($role, $user_id);
        }
        return;
    }

    public function takeoffUserAssig ($item_name, $user_id)
    {
        $auth = Yii::$app->getAuthManager();
        if ($item_name === 'admin') {
            $role = $auth->getRole('admin');
            $auth->revoke($role,$user_id);
        }
        if ($item_name === 'user') {
            $role = $auth->getRole('user');
            $auth->revoke($role,$user_id);
        }
    }
}