<?php

namespace app\models\admin;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use kartik\daterange\DateRangeBehavior;
use app\models\user\User;

class Search extends Model
{
    public $username;
    public $email;
    public $created_at;
    public $createTimeRange;
    public $createTimeStart;
    public $createTimeEnd;
    public $lastLoginTimeRange;
    public $lastLoginTimeStart;
    public $lastLoginTimeEnd;
    public $last_login;
    public $last_login_ip;
    public $status;
    public $register_ip;

    /**
     * @return array правила валидации
     */
    public function rules()
    {
        return[
            [['username', 'email', 'created_at', 'status', 'last_login', 'last_login_ip', 'register_ip' ], 'safe',],
            [['createTimeRange'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
            [['lastLoginTimeRange'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
        ];
    }

    /**
     * Создания временных промежутков для фильтрации
     * 
     * @return array
     */
    public function behaviors()
    {
        return[
            [
                'class'=>DateRangeBehavior::className(),
                'attribute'=>'createTimeRange',
                'dateStartAttribute'=>'createTimeStart',
                'dateEndAttribute'=>'createTimeEnd',
            ],
            [
                'class'=>DateRangeBehavior::className(),
                'attribute'=>'lastLoginTimeRange',
                'dateStartAttribute'=>'lastLoginTimeStart',
                'dateEndAttribute'=>'lastLoginTimeEnd',
            ],
        ];
    }

    /**
     * Фильтрация пользователей
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ]
        ]);

        $this->load($params);

        if($this->validate() === false) {
            return $dataProvider;
        }

        if ($this->createTimeStart !== NULL && $this->createTimeEnd !== NULL) {
            $this->createTimeStart = date("Y.m.d H:i:s", $this->createTimeStart);
            $this->createTimeEnd = date("Y.m.d H:i:s", $this->createTimeEnd);
        }

        if ($this->lastLoginTimeStart !== NULL && $this->lastLoginTimeEnd !== NULL) {
            $this->lastLoginTimeStart = date("Y.m.d H:i:s", $this->lastLoginTimeStart);
            $this->lastLoginTimeEnd = date("Y.m.d H:i:s", $this->lastLoginTimeEnd);
        }

        $query->andFilterWhere([
            'username' => $this->username,
            'email' => $this->email,
            'status' => $this->status,
            'last_login_ip' => $this->last_login_ip,
            'register_ip' => $this->register_ip,
            ]);

        $query->andFilterWhere(['>=','user.created_at',$this->createTimeStart])
            ->andFilterWhere(['<','user.created_at',$this->createTimeEnd]);

        $query->andFilterWhere(['>=','user.last_login',$this->lastLoginTimeStart])
            ->andFilterWhere(['<','user.last_login',$this->lastLoginTimeEnd]);

        return $dataProvider;
    }
}