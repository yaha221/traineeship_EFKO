<?php

namespace app\controllers;

use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\models\calculate\CalculatedForm;
use app\models\repositories\DataRepository;
use app\models\user\Request;
use app\models\releaseControl\FeatureEnums;
use app\components\releaseControl\ReleaseControlComponent;

/**
 * HomeController отвечает за работу калькулятором доставки
 */
class CalculateController extends Controller
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
                    'feedback' => ['POST'],
                    'index' => ['GET','POST'],
                ],
            ],
            [
                'class' => \yii\filters\AjaxFilter::class,
                'only' => ['feedback'],
            ],
        ];
    }

    /**
     * Отображение домашней страницы и ajax валидация формы
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $releaseController = \Yii::$container->get(ReleaseControlComponent::class);

        if ($releaseController->isEnabled(FeatureEnums::USE_VUE_JS_FORM_REALIZATION) === true) {
            return $this->render('vue');
        }

        \Yii::$app->redis->auth('lol2lol');
        \Yii::$app->redis->set('mykey', 'some value');
        dd(\Yii::$app->redis->get('mykey'));

        $calculatedForm  = new CalculatedForm();
        $repository = new DataRepository();

        if (Yii::$app->request->isAjax && $calculatedForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($calculatedForm);
        }

        if (Yii::$app->user->isGuest === false) {
            $cookies = Yii::$app->request->cookies;
            $isAlert = false;

            $user = $repository->findUsernameById(Yii::$app->user->id);
            $username = $user['username'];
            
            if ($cookies->has('isAlert' . $username)) {
                $isAlert = $cookies['isAlert'. $username]->value;
            }

            return $this->render('index', [
                'calculatedForm' => $calculatedForm ,
                'repository' => $repository,
                'username' => $username,
                'isAlert' => $isAlert,
            ]);
        }

        return $this->render('index', [
            'calculatedForm' => $calculatedForm ,
            'repository' => $repository,
        ]);
    }

    /**
     * Формирует таблицу по введённым данным в форму в виде ajax запроса
     * 
     * @return mixed
     */
    public function actionFeedback()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $calculatedForm  = new CalculatedForm();

        if ($calculatedForm->load(Yii::$app->request->post()) === false || $calculatedForm->validate() === false) {
            throw new NotFoundHttpException('Страница не найдена!');
        }

        $repository = new DataRepository();

        $month = $repository->findMonthById($calculatedForm->month);
        $tonnage = $repository->findTonnageById($calculatedForm->tonnage);
        $type = $repository->findTypeById($calculatedForm->type);

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

        if (Yii::$app->user->isGuest === false) {
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

            $newRequest = new Request();

            try {
                $newRequest->createUserRequest($userRequest);
            } catch (\Exception $e) {
                Yii::info($e->getMessage());
            }
        }

        return $this->renderAjax('result', [
            'calculatedForm' => $calculatedForm,
            'repository' => $repository,
            'monthData' => $month['name'],
            'tonnageData' => $tonnage['value'],
            'typeId' => $type['id'],
            'type' => $type['name'],
            'costTable' => $costTable,
            'costValue' => $costData['value'],
        ]);
    }
}