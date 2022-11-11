<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\web\Response;
use app\models\CalculatedForm;
use app\models\Data;

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
        $data = new Data();
        if (Yii::$app->request->isAjax && $calculatedForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($calculatedForm);
        }
        return $this->render('index', [
            'calculatedForm' => $calculatedForm ,
            'data' => $data,
        ]);
    }

    /**
     * Формирует ответ на ajax запрос
     * 
     * @return string
     */
    public function actionFeedback()
    {
        $calculatedForm  = new CalculatedForm();
        $data = new Data();
        if($calculatedForm->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            return $this->renderAjax('result', [
                'calculatedForm' => $calculatedForm ,
                'data' => $data,
            ]);
        }
    }
}
