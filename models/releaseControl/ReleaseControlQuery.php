<?php

namespace app\models\releaseControl;


use yii\db\ActiveQuery;

class ReleaseControlQuery extends ActiveQuery
{
    public function __construct($modelClass)
    {
        parent::__construct($modelClass);
    }

    /**
     * @param string $periodFrom
     * @param string $periodTo
     * @return void
     */
    public function defineFilterPeriod($tableName, $periodFrom, $periodTo)
    {
        $this->andFilterWhere(['between', $tableName, $periodFrom, $periodTo]);
    }
}