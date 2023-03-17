<?php

namespace app\models\bases;

use app\controllers\AdminController;
use yii\base\Module;
use yii\db\ActiveRecord;

use yii\web\NotFoundHttpException;

abstract class RepositoryServiceController extends AdminController
{
    /**
     * Имя класса сервиса.
     * @var string
     */
    protected static $serviceClass = BaseModelService::class;
    
    /**
     * @var BaseModelService
     */
    protected $service;
    
    /**
     * @var BaseModelRepository
     */
    protected $repository;

    /**
     * Сообщение об ошибке,
     * если сущность не найдена.
     * 
     * @var string
     */
    protected $entityNotFoundMessage = 'The requested page does not exist.';

    public function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $service = static::$serviceClass;

        $this->service = $service::getInstance();

        $this->repository = $this->service->getRepository();

    }

    /**
     * @param $id
     * @param mixed ...$args
     * @return ActiveRecord|null
     */
    protected function findEntity($id, ...$args)
    {
        $repository = $this->repository;

        return $repository::findOne($id);
    }

    /**
     * @param  int $id
     * @return void
     * @throws NotFoundHttpException
     */
    protected function defineRepositoryModel($id, ...$args)
    {
        $model = $this->findEntity($id, ...$args);

        if (! ($model instanceof ActiveRecord)) {
            throw new NotFoundHttpException($this->entityNotFoundMessage);
        }

        $this->repository->setEntity($model);
    }

}
