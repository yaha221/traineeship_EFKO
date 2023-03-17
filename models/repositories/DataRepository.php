<?php

namespace app\models\repositories;

use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Набор методов для обращения к базе данных.
*/
class DataRepository
{
    /**
     * Находит имена пользователей совпадающие с введённым
     * 
     * @param string $username
     * @return array имена
     */
    public function findUsersByUsername($username): array
    {
        $query = (new Query())
            ->select('id, username AS text')
            ->from('user')
            ->where(['like', 'username', $username]);

        return $query->createCommand()->queryAll();
    }

    /**
     * Находит имена пользователей совпадающие с введённым для формы админа
     * 
     * @param string $username
     * @return array имена
     */
    public function findUsersByUsernameForUsers($username): array
    {
        $query = (new Query())
            ->select('username AS id, username AS text')
            ->from('user')
            ->where(['like', 'username', $username]);

        return $query->createCommand()->queryAll();
    }

    /**
     * Находит все ip входа пользователей совпадающие с введённым
     * 
     * @param string $lastLoginIp
     * @return array ip входа
     */
    public function findUsersByLastLoginIp($lastLoginIp): array
    {
        $query = (new Query())
            ->select('last_login_ip AS id, last_login_ip AS text')
            ->from('user')
            ->where(['like', 'last_login_ip', $lastLoginIp]);

        return $query->createCommand()->queryAll();
    }

    /**
     * Находит ip регистрации пользователей совпадающие с введённым
     * 
     * @param string $registerIp
     * @return array ip регистрации
     */
    public function findUsersByRegisterIp($registerIp): array
    {
        $query = (new Query())
            ->select('register_ip AS id, register_ip AS text')
            ->from('user')
            ->where(['like', 'register_ip', $registerIp]);

        return $query->createCommand()->queryAll();
    }

    /**
     * Находит ip регистрации пользователей совпадающие с введённым
     * 
     * @param string $email
     * @return array ip регистрации
     */
    public function findUsersByEmail($email): array
    {
        $query = (new Query())
            ->select('email AS id, email AS text')
            ->from('user')
            ->where(['like', 'email', $email]);

        return $query->createCommand()->queryAll();
    }

    /**
     * Находит имя пользователя по ид
     * 
     * @param integer $id
     * @return array имя
     */
    public function findUsernameById($id): array
    {
        $query = (new Query())
            ->select('username')
            ->from('user')
            ->where(['id' => $id])
            ->one();

        return $query;
    }

    /**
     * Находит все имена
     * 
     * @return array имена
     */
    public function findUsernames(): array
    {
        $query = (new Query())
            ->select(['id','username'])
            ->from('user')
            ->all();

        $result = ArrayHelper::map($query, 'id', 'username');

        return $result;
    }

    /**
     * Находит все имена для формы админа
     * 
     * @return array имена
     */
    public function findUsernamesForUsers(): array
    {
        $query = (new Query())
            ->select(['username'])
            ->from('user')
            ->all();

        $result = ArrayHelper::map($query, 'username', 'username');

        return $result;
    }

    /**
     * Находит все имена
     * 
     * @return array имена
     */
    public function findEmails(): array
    {
        $query = (new Query())
            ->select(['email'])
            ->from('user')
            ->all();

        $result = ArrayHelper::map($query, 'email', 'email');

        return $result;
    }

    /**
     * Находит все ip входа
     * 
     * @return array ip входа
     */
    public function findLastLoginIp(): array
    {
        $query = (new Query())
            ->select(['last_login_ip'])
            ->from('user')
            ->all();

        $result = ArrayHelper::map($query, 'last_login_ip', 'last_login_ip');

        return $result;
    }

    /**
    * Находит все ip регистрации
    * 
    * @return array ip регистрации
    */
   public function findRegisterIp(): array
   {
        $query = (new Query())
            ->select(['register_ip'])
            ->from('user')
            ->all();

        $result = ArrayHelper::map($query, 'register_ip', 'register_ip');

       return $result;
   }

    /**
     * Находит все месяца
     * 
     * @return array месяца
     */
    public function findMonths(): array
    {
        $query = (new Query())
            ->select(['*'])
            ->from('month')
            ->all();

        return $query;
    }

    /**
     * Находит все тоннажи
     * 
     * @return array тоннажи
     */
    public function findTonnages(): array
    {
        $query = (new Query())
            ->select(['*'])
            ->from('tonnage')
            ->all();

        return $query;
    }

    /**
     * Находит все типы
     * 
     * @return array типы
     */
    public function findTypes(): array
    {
        $query = (new Query())
            ->select(['*'])
            ->from('type')
            ->all();

        return $query;
    }

    /**
     * Находит месяц по номеру
     * 
     * @param integer $id
     * @return array месяц
     */
    public function findMonthById(int $id): array
    {
        $query = (new Query())
            ->select(['*'])
            ->from('month')
            ->where(['id' => $id])
            ->one();
        
        return $query;
    }

    /**
     * Находит тоннаж по номеру
     * 
     * @param integer $id
     * @return array тоннаж
     */
    public function findTonnageById(int $id): array
    {
        $query = (new Query())
            ->select(['*'])
            ->from('tonnage')
            ->where(['id' => $id])
            ->one();
        
        return $query;
    }

    /**
     * Находит тип по номеру
     * 
     * @param integer $id
     * @return array тип
     */
    public function findTypeById(int $id): array
    {
        $query = (new Query())
            ->select(['*'])
            ->from('type')
            ->where(['id' => $id])
            ->one();
        
        return $query;
    }

    /**
     * Находит месяц по имени
     * 
     * @param string $name
     * @return array месяц
     */
    public function findMonthByName(string $name)
    {
        $query = (new Query())
            ->select(['id', 'name'])
            ->from('month')
            ->where(['name' => $name])
            ->one();
        
        return $query;
    }

    /**
     * Находит тоннаж по значению
     * 
     * @param integer $value
     * @return array тоннаж
     */
    public function findTonnageByValue(int $value)
    {
        $query = (new Query())
            ->select(['id', 'value'])
            ->from('tonnage')
            ->where(['value' => $value])
            ->one();
        
        return $query;
    }

    /**
     * Находит тип по имени
     * 
     * @param string $name
     * @return array тип
     */
    public function findTypeByName(string $name)
    {
        $query = (new Query())
            ->select(['id', 'name'])
            ->from('type')
            ->where(['name' => $name])
            ->one();
        
        return $query;
    }

    /**
     * Находит весь прайс
     * 
     * @return array прайс
     */
    public function findCostAll(): array
    {
        $query = (new Query())
            ->select([
                'c.*',
                'monthName' => 'm.name',
                'tonnageValue' => 'tn.value',
                'typeName' => 'tp.name',
                ])
            ->from(['c' => 'cost'])
            ->leftJoin(['m' => 'month'], 'm.id = c.month_id')
            ->leftJoin(['tn' => 'tonnage'], 'tn.id = c.tonnage_id')
            ->leftJoin(['tp' => 'type'], 'tp.id = c.type_id')
            ->all();

        return $query;
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
        $query = (new Query())
            ->select(['*'])
            ->from(['cost'])
            ->where([
                'month_id' => $monthId,
                'tonnage_id' => $tonnageId,
                'type_id' => $typeId,
                ])
            ->one();

        return $query;
    }
}