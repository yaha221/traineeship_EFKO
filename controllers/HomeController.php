<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\web\Response;
use app\models\CalculatedForm;
use app\models\repositories\DataRepository;

class HomeController extends Controller
{
    /**
     * Отображение домашней страницы и ajax валидация формы
     * 
     * @return string|string
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
     * @return string
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

        return $this->renderAjax('result', [
            'calculatedForm' => $calculatedForm,
            'months' => $repository->findMonths(),
            'tonnages' => $repository->findTonnages(),
            'types' => $repository->findTypes(),

            'typeData' => $type,
            'monthData' => $month,
            'tonnageData' => $tonnage,

            'costTable' => $costTable,
            'typeKey' => $type['id'],

            'costValue' => $costData['value'],
        ]);
    }
}
