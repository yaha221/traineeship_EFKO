<?php

namespace app\models\bases;

use yii\base\Model;

abstract class BaseModelService
{
    /**
     * @var BaseModelRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected static $repositoryClass = BaseModelRepository::class;

    public function __construct($repository)
    {
        $this->setRepository($repository);
    }

    /**
     * Установка репозитория.
     *
     * @param  BaseModelRepository $repository
     * @return void
     */
    public function setRepository(BaseModelRepository $repository)
    {
        if (! $repository instanceof static::$repositoryClass) {
            throw new \RuntimeException('Класс репозитория должен быть унаследован от ' . static::$repositoryClass);
        }
        
        $this->repository = $repository;
    }

    /**
     * @return BaseModelRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param string $relation
     * @return string
     */
    protected function getRelationMethod($relation)
    {
        return 'has' . ucfirst($relation);
    }

    /**
     * @param string $relation
     * @return bool
     */
    protected function hasRelationMethod($relation)
    {
        return method_exists(
            $this->repository,
            $this->getRelationMethod($relation)
        );
    }

    /**
     * @param string $relation
     * @param string $property
     * @param null $default
     * @return mixed
     */
    protected function getRelationAttribute($relation, $property, $default = null)
    {
        $method = $this->getRelationMethod($relation);

        $check = $this->hasRelationMethod($relation) ? $this->repository->{$method}() : $this->repository->has($relation);

        $entity = $this->repository->getEntity();

        return  $check ? $entity->$relation->{$property} : $default;
    }
    
    /**
     * Выводит значение аттрибута.
     *
     * @param  string $relation
     * @param  string $property
     * @param  string $default
     * @param  $callback
     * @return string
     */
    public function printRelationAttribute(
        $relation,
        $property,
        $default = 'Не задано',
        $callback = null
    )
    {
        
        $value = $this->getRelationAttribute($relation, $property, false);
        
        if ($value === false) {
            return $default;
        }

        if (is_callable($callback)) {
            return $callback($value);
        }

        return $value;
    }

    /**
     * Возвращает аттрибут сущности.
     *
     * @param  string $attribute
     * @param  string $default
     * @param  \Closure $callback
     * @return string
     */
    public function printAttribute(
        $attribute,
        $default = 'Не задано',
        $callback = null
    )
    {
        $entity = $this->repository->getEntity();

        $value = $entity->getAttribute($attribute);

        if ($value === null) {
            return (string) $default;
        }

        if (is_callable($callback)) {
            return (string) $callback($value);
        }
        
        return (string) $value;
    }

    /**
     * @return BaseModelService
     */
    public static function getInstance()
    {
        $repository = static::$repositoryClass;
        $repositoryInstance = $repository::getInstance();

        return new static($repositoryInstance);
    }

    /**
     * Фабрика формы новой модели.
     *
     * @param  string $formClass
     * @param  mixed ...$args
     * @return Model
     */
    public function buildForm($formClass, ...$args)
    {
        $entity = $this->repository->getEntity();

        $form = new $formClass(...$args);
        
        $attributes = [$form->formName() => $entity->getAttributes()];

        $form->load($attributes);
        
        return $form;
    }

    /**
     * Сохранение данных из формы.
     *
     * @param  Model $form
     * @param  bool $safe
     * @return bool
     */
    public function saveForm($form, $safe = true, $beforeSave = null)
    {
        if (! $form->validate()) {
            return false;
        }

        $entity = $this->repository->getEntity();
        
        $attributes = [$entity->formName() => $form->getAttributes()];

        $entity->load($attributes);

        if (! $entity->validate()) {
            $form->addErrors($entity->getErrors());

            return false;
        }

        if (is_callable($beforeSave)) {
            $beforeSave();
        }

        return $this->repository->save($safe);
    }

}
