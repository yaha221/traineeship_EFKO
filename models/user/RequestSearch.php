<?php

namespace app\models\user;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use kartik\daterange\DateRangeBehavior;
use app\models\user\Request;

class RequestSearch extends Model
{
    public $month;
    public $type;
    public $tonnage;
    public $user_id;
    public $created_at;
    public $createTimeRange;
    public $createTimeStart;
    public $createTimeEnd;

    /**
     * @return array правила валидации полей формы поиска
     */
    public function rules()
    {
        return [
            [['month', 'type', 'tonnage', 'created_at','user_id'], 'safe',],
            [['createTimeRange'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
        ];
    }

    public function behaviors()
    {
        return[
            [
                'class' => DateRangeBehavior::className(),
                'attribute' => 'createTimeRange',
                'dateStartAttribute' => 'createTimeStart',
                'dateEndAttribute' => 'createTimeEnd',
            ]
        ];
    }
    
    /**
     * Фильтрация истории запросов
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        if (Yii::$app->user->can('admin') === true) {
            $query = Request::find()
                ->joinWith('user');

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        }
        
        if (Yii::$app->user->can('admin') === false) {
            $query = Request::find()
                ->where(['user_id' => Yii::$app->user->id]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        }

        $this->load($params);
        if ($this->validate() === false) {
            return $dataProvider;
        }

        if ($this->createTimeStart !== NULL && $this->createTimeEnd !== NULL) {
        $this->createTimeStart = date("Y.m.d H:i:s", $this->createTimeStart);
        $this->createTimeEnd = date("Y.m.d H:i:s", $this->createTimeEnd);
        }

        $query->andFilterWhere([
            'month' => $this->month,
            'type' => $this->type,
            'tonnage' => $this->tonnage,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['>=','user_request.created_at', $this->createTimeStart])
            ->andFilterWhere(['<','user_request.created_at', $this->createTimeEnd]);

        return $dataProvider;
    }
}