<?php

namespace app\models\releaseControl;


use Jenssegers\Date\Date;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ReleaseControl extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%msdt_release_control}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function () {
                    return Date::parse()
                        ->format('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * @return ReleaseControlQuery
     */
    public static function find()
    {
        return new ReleaseControlQuery(static::class);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'release_control_id' => 'ID',
            'description'        => 'Описание',
            'active'             => 'Активность',
            'created_at'         => 'Дата создания',
            'updated_at'         => 'Дата обновления',
        ];
    }
}