<?php

namespace app\models\bases;

use yii\db\ActiveRecord;
use yii\db\Query;

abstract class BaseModelRepository
{
    /**
     * @var ActiveRecord
     */
    protected $entity;

    /**
     * @var string
     */
    protected $tablePrefix = '';

    /**
     * @var string
     */
    protected static $entityClass = ActiveRecord::class;

    public function __construct(ActiveRecord $entity)
    { 
        $this->setEntity($entity);
    }

    /**
     * @return Query
     */
    public function newQuery()
    {
        $class = static::$entityClass;

        $query = $class::find();

        if (method_exists($query, 'setTablePrefix')) {
            $prefix = $this->tablePrefix . '.';
            
            $query->setTablePrefix($prefix);
        }
        
        return $query->from($class::tableName() . ' ' . $this->tablePrefix);
    }

    /**
     * @param int $id
     * @return null|ActiveRecord
     */
    public static function findOne($id)
    {
        $class = static::$entityClass;

        return $class::findOne($id);
    }

    /**
     * Установка сущности.
     *
     * @param  ActiveRecord $entity
     * @return void
     */
    public function setEntity(ActiveRecord $entity)
    {
        if (! $entity instanceof static::$entityClass) {
            throw new \RuntimeException('Класс сущности должен быть унаследован от ' . static::$entityClass);
        }
        
        $this->entity = $entity;
    }

    /**
     * @return ActiveRecord
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param string $relation
     * @return boolean
     */
    public function has($relation)
    {
        $method = 'get' . ucfirst($relation);

        return $this->entity->{$method}()->exists();
    }

    /**
     * @param string $relation
     * @return boolean
     */
    public function hasNo($relation)
    {
        return ! $this->has($relation);
    }

    /**
     * @param $attribute
     * @return mixed
     */
    public function getAttribute($attribute)
    {
        return $this->entity->{$attribute};
    }

    /**
     * @param  array $attributes
     * @return void
     */
    public function load(array $attributes = [])
    {
        $this->entity->load([
            $this->entity->formName() =>  $attributes
        ]);
    }

    /**
     * @param  bool $safe
     * @return bool
     */
    public function save($safe)
    {
        return $this->entity->save($safe);
    }

    /**
     * @param  null|ActiveRecord $model
     * @return BaseModelRepository
     */
    public static function getInstance($model = null)
    {
        $entity = $model ?: new static::$entityClass();

        return new static($entity);
    }

    /**
     * @return bool
     */
    public function isDirty()
    {
        return (bool) count($this->entity->getDirtyAttributes()) === true;
    }

    /**
     * @param mixed $attribute
     * @param mixed $value
     * @return void
     */
    public function setAttribute($attribute, $value)
    {
        $this->entity->setAttribute($attribute, $value);
    }
}
