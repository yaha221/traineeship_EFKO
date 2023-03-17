<?php

namespace app\models\releaseControl;

use Jenssegers\Date\Date;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ReleaseControlSearch extends Model
{
    /**
     * @var int
     */
    public $release_control_id;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $active;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var string
     */
    public $updated_at;

    /**
     * @var array
     */
    protected $periodValues;

    /**
     * @var string
     */
    protected $periodFrom;

    /**
     * @var string
     */
    protected $periodTo;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'release_control_id',
                    'description',
                    'active',
                    'updated_at',
                ],
                'safe',
            ],
        ];
    }

    /**
     * Преобразовать строку
     * периода в массив.
     *
     * @param string $date
     * @return array
     */
    protected function explodePeriod($date)
    {
        return explode(' - ', $date);
    }

    /**
     * Инициализация значения
     * массива периода.
     *
     * @param string $date
     * @return void
     */
    protected function initPeriodValues($date)
    {
        $this->periodValues = array_values($this->explodePeriod($date));
    }

    /**
     * Инициализация даты начала
     * фильтрации.
     *
     * @return void
     */
    protected function initPeriodFrom()
    {
        $this->periodFrom = array_shift($this->periodValues);

        if ($this->periodFrom) {
            $this->periodFrom = Date::parse($this->periodFrom)->format('Y-m-d H:i:s');
        }
    }

    /**
     * Инициализация даты окончания
     * фильтрации.
     *
     * @return void
     */
    protected function initPeriodTo()
    {
        $this->periodTo = array_shift($this->periodValues);

        if ($this->periodTo) {
            $this->periodTo = Date::parse($this->periodTo)->format('Y-m-d H:i:s');
        }
    }

    /**
     * Возвращает провайдер
     * данных.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ReleaseControl::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'release_control_id' => $this->release_control_id,
            'description' => $this->description,
            'active' => $this->active,
        ]);

        if ($this->updated_at) {
            $this->initPeriodValues($this->updated_at);

            $this->initPeriodFrom();

            $this->initPeriodTo();

            $query->defineFilterPeriod('updated_at', $this->periodFrom, $this->periodTo);
        }

        return $dataProvider;
    }
}