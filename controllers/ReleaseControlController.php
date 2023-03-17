<?php

namespace app\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use app\models\bases\RepositoryServiceController;
use app\models\releaseControl\ReleaseControlSearch;
use app\models\releaseControl\ReleaseControlService;

class ReleaseControlController extends RepositoryServiceController
{
    /**
     * @see RepositoryServiceController
     */
    protected static $serviceClass = ReleaseControlService::class;

    /**
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('admin') === false){
            throw new ForbiddenHttpException('Доступ запрещён');
        }

        $request = Yii::$app->request;
        
        $searchModel = new ReleaseControlSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        $repository = $this->repository;
        
        $service = $this->service;

        if ($request->isAjax) {
            return $this->renderAjax('_grid_manage',
                compact(
                    'dataProvider',
                    'searchModel',
                    'repository',
                    'service'
                )
            );
        }

        return $this->render('index', [
            'service' => $service,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'repository' => $repository,
        ]);
    }

    /**
     * @return void
     */
    public function actionChange()
    {
        if (Yii::$app->user->can('admin') === false){
            throw new ForbiddenHttpException('Доступ запрещён');
        }

        $request = Yii::$app->request;
        $id = $request->get('id');
        $active = $request->get('value');

        $this->defineRepositoryModel($id);

        $this->repository->setAttribute('active', (int) filter_var($active, FILTER_VALIDATE_BOOLEAN));

        $this->repository->save(false);
    }

}