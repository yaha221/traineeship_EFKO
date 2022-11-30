<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use app\models\CalculatedForm;
use app\models\repositories\DataRepository;
use nkostadinov\user\models\User;
use app\models\UserRequest;
use yii\web\ForbiddenHttpException;

/**
 * HomeController отвечает за работу калькулятором доставки
 */
class HomeController extends Controller
{
    /**
     * Отображение домашней страницы и ajax валидация формы
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $calculatedForm  = new CalculatedForm();
        $repository = new DataRepository();
        $months = $repository->findMonths();
        $tonnages = $repository->findTonnages();
        $types = $repository->findTypes();
        if (Yii::$app->request->isAjax && $calculatedForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($calculatedForm);
        }
        if (Yii::$app->user->isGuest === false) {
            $user = User::findById(Yii::$app->user->id);
            $username = $user['username'];
            $is_alert = $user['is_alert'];
            return $this->render('index', [
                'calculatedForm' => $calculatedForm ,
                'months' => $months,
                'tonnages' => $tonnages,
                'types' => $types,
                'username' => $username,
                'is_alert' => $is_alert,
            ]);
        }
        return $this->render('index', [
            'calculatedForm' => $calculatedForm ,
            'months' => $months,
            'tonnages' => $tonnages,
            'types' => $types,
        ]);
    }

    /**
     * Формирует ответ на ajax запрос
     * 
     * @return mixed
     */
    public function actionFeedback()
    {
        if (Yii::$app->request->isAjax === false) {
            return $this->redirect('/');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $calculatedForm  = new CalculatedForm();

        if ($calculatedForm->load(Yii::$app->request->post()) === false || $calculatedForm->validate() === false) {
            return $this->redirect('/');
        }

        $repository = new DataRepository();

        $month = $repository->findMonthById((int) $calculatedForm->month);
        $tonnage = $repository->findTonnageById((int) $calculatedForm->tonnage);
        $type = $repository->findTypeById((int) $calculatedForm->type);

        $costs = $repository->findCostAll();
        $costData = $repository->findCostOneByParams($month['id'], $tonnage['id'], $type['id']);

        $costTable = [];

        foreach ($costs as $item) {
            $costTable[$item['type_id']][$item['tonnage_id']][$item['month_id']] = $item['value'];
        }

        foreach ($costTable as $key => $value) {
            if ($key !== intval($type['id'])) {
                unset($costTable[$key]);
            }
        }

        if(Yii::$app->user->isGuest === false) {
            $months = $repository->findMonths();
            $tonnages = $repository->findTonnages();
            $userRequest = [
                'user_id' => Yii::$app->user->id,
                'month' => $month['name'],
                'type' => $type['name'],
                'tonnage' => $tonnage['value'],
                'result_value' => $costData['value'],
                'result_table' => serialize($costTable[$type['id']]),
                'months_now' => serialize($months),
                'tonnages_now' => serialize($tonnages),
            ];
            $newUserRequest = new UserRequest();
            try {
                $newUserRequest->createUserRequest($userRequest);
            } catch (\Exception $e) {
                Yii::info($e->getMessage());
            }
        }

        return $this->renderAjax('result', [
            'calculatedForm' => $calculatedForm,
            'months' => $repository->findMonths(),
            'tonnages' => $repository->findTonnages(),
            'types' => $repository->findTypes(),

            'typeData' => $type,
            'monthData' => $month,
            'tonnageData' => $tonnage,

            'costTable' => $costTable,
            'type' => $type['name'],

            'costValue' => $costData['value'],
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
        $data = UserRequest::findOne($id);
        if ($data === null) {
            return $this->redirect('/');
        }
        $user = User::findById($data['user_id']);
        $username = $user['username'];
        if ($user->id !== Yii::$app->user->id  && !Yii::$app->user->can('admin')){
            return $this->redirect('/');
        }
        return $this->render('table', [
            'username' => $username,
            'type' => $data['type'],
            'month' => $data['month'],
            'tonnage' => $data['tonnage'],
            'result' => $data['result_value'],
            'createdDate' => $data['created_at'],
            'months' => unserialize($data['months_now']),
            'tonnages' => unserialize($data['tonnages_now']),
            'costTable' => unserialize($data['result_table']),
        ]);
    }

    /**
     * Отправляет данные для отрисоки запросов пользователя
     * 
     * @return mixed
     */
    public function actionHistory()
    {
        if (Yii::$app->user->can('admin')) {
                    $dataProvider = new ActiveDataProvider([
            'query' => UserRequest::find(),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        return $this->render('history', [
            'dataProvider' => $dataProvider,
        ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => UserRequest::find()
                ->where(['user_id' => Yii::$app->user->id]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        return $this->render('history', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Убирает у пользователя уведомления об отслеживании его запросов
     * 
     * @return mixed
     */
    public function actionRemovealert()
    {
        if (Yii::$app->request->isAjax === false) {
            return $this->redirect('/');
        }

        if (Yii::$app->request->post() === false) {
            return $this->redirect('/');
        }

        $user = User::findById(Yii::$app->user->id);
        $user->removeAlert(Yii::$app->user->id);

        return;
    }

    /**
     * Удаляет запрос пользователя из истории
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) 
    {
        if (!\Yii::$app->user->can('admin')) {
            throw new ForbiddenHttpException('Access denied');
        }
        UserRequest::findOne($id)->delete();
        return $this->redirect('history');
    }

}