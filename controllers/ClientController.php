<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\models\repositories\DataRepository;
use app\models\user\Request;
use app\models\user\RequestSearch;

/**
 * ClientController отвечает за работу калькулятором доставки
 */
class ClientController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return[
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'removealert' => ['POST'],
                ],
            ],
            [
                'class' => \yii\filters\AjaxFilter::class,
                'only' => ['removealert'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Отправляет данные для отрисоки запросов пользователя
     * 
     * @return mixed
     */
    public function actionHistory()
    {
        $repository = new DataRepository();
        $searchModel = new RequestSearch([]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('history', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'repository' => $repository,
        ]);
    }

    /**
     * Отправляет данные для отрисовки запроса из истории пользователя
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionTable($id)
    {
        $request = Request::find()->joinWith('user')->where(['user_request.id' => $id])->one();

        if ($request === null) {
            throw new NotFoundHttpException('Страница не найдена!');
        }

        if ($request->user->id !== Yii::$app->user->id  && Yii::$app->user->can('admin') === false) {
            throw new ForbiddenHttpException('Доступ запрещён');
        }

        return $this->render('table', [
            'model' => $request,
        ]);
    }
    
    /**
     * Убирает у пользователя уведомления об отслеживании его запросов
     */
    public function actionRemovealert()
    {
        $repository = new DataRepository();

        $user = $repository->findUsernameById(Yii::$app->user->id);
        $username = $user['username'];

        $cookies = Yii::$app->response->cookies;
        $cookies->remove('isAlert' . $username);
    }

    /**
     * Удаляет запрос пользователя из истории
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) 
    {
        if (Yii::$app->user->can('admin') === false) {
            throw new ForbiddenHttpException('Доступ запрещён');
        }

        Request::findOne($id)->delete();

        return $this->redirect('history');
    }

    /**
     * Ajax-поиск по именам пользователей
     * 
     * @param string $q
     * @return array
     */
    public function actionUsersearch($q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $username = ['results' => ['id' => '', 'text' => '']];
        $repository = new DataRepository();

        if(empty($q) === false) {
            $username['results'] = $repository->findUsersByUsername($q);
        }

        return $username;
    }
}