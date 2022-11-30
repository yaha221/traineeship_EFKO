<?php

namespace app\models\repositories;

use yii\db\Expression;
use yii\db\Query;

/**
 * Набор методов для обращения к базе данных.
*/
class DataRepository
{
    /**
     * Находит все месяца
     * 
     * @return array месяца
     */
    public function findMonths(): array
    {
        $data = (new Query())->select(['*'])
            ->from('month')
            ->all();

        $result = [];

        foreach ($data as $month) {
            $result[$month['id']] = $month['name'];
        }

        return $result;
    }

    /**
     * Находит все тоннажи
     * 
     * @return array тоннажи
     */
    public function findTonnages(): array
    {
        $data = (new Query())->select(['*'])
            ->from('tonnage')
            ->all();

        $result = [];

        foreach ($data as $tonnage) {
            $result[$tonnage['id']] = $tonnage['value'];
        }

        return $result;
    }

    /**
     * Находит все типы
     * 
     * @return array типы
     */
    public function findTypes(): array
    {
        $data = (new Query())->select(['*'])
            ->from('type')
            ->all();

        $result = [];

        foreach ($data as $type) {
            $result[$type['id']] = $type['name'];
        }

        return $result;
    }

    /**
     * Находит месяц по номеру
     * 
     * @param integer $id
     * @return array месяц
     */
    public function findMonthById(int $id): array
    {
        return (new Query())->select(['*'])
            ->from('month')
            ->where(['id' => $id])
            ->one();
    }

    /**
     * Находит тоннаж по номеру
     * 
     * @param integer $id
     * @return array тоннаж
     */
    public function findTonnageById(int $id): array
    {
        return (new Query())->select(['*'])
            ->from('tonnage')
            ->where(['id' => $id])
            ->one();
    }

    /**
     * Находит тип по номеру
     * 
     * @param integer $id
     * @return array тип
     */
    public function findTypeById(int $id): array
    {
        
        return (new Query())->select(['*'])
            ->from('type')
            ->where(['id' => $id])
            ->one();
    }

    /**
     * Находит месяц по имени
     * 
     * @param string $name
     * @return array месяц
     */
    public function findMonthByName(string $name)
    {
        return (new Query())->select(['id'])
            ->from('month')
            ->where(['name' => $name])
            ->one();
    }

    /**
     * Находит тоннаж по значению
     * 
     * @param integer $value
     * @return array тоннаж
     */
    public function findTonnageByValue(int $value)
    {
        return (new Query())->select(['id'])
            ->from('tonnage')
            ->where(['value' => $value])
            ->one();
    }

    /**
     * Находит тип по имени
     * 
     * @param string $name
     * @return array тип
     */
    public function findTypeByName(string $name)
    {
        return (new Query())->select(['id'])
            ->from('type')
            ->where(['name' => $name])
            ->one();
    }

    /**
     * Находит весь прайс
     * 
     * @return array прайс
     */
    public function findCostAll(): array
    {
        return (new Query())->select([
            'c.*',
            'monthName' => 'm.name',
            'tonnageValue' => 'tn.value',
            'typeName' => 'tp.name',
        ])
        ->from(['c' => 'cost'])
        ->leftJoin(['m' => 'month'], ['m.id' => new Expression('c.month_id')])
        ->leftJoin(['tn' => 'tonnage'], ['tn.id' => new Expression('c.tonnage_id')])
        ->leftJoin(['tp' => 'type'], ['tp.id' => new Expression('c.type_id')])
        ->all();
    }

    /**
     * Находит цену по индексам
     * 
     * @param integer $monthId
     * @param integer $tonnageId
     * @param integer $typeId
     * @return array цену
     */
    public function findCostOneByParams($monthId, $tonnageId, $typeId): array
    {
        return (new Query())->select(['*'])
            ->from(['c' => 'cost'])
            ->where([
                'month_id' => $monthId,
                'tonnage_id' => $tonnageId,
                'type_id' => $typeId,
            ])
            ->one();
    }
}