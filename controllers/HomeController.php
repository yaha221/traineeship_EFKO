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
    public function actionIndex(){
        $calculatedForm  = new CalculatedForm();
        $data = new Data();
        if (Yii::$app->request->isAjax && $calculatedForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($calculatedForm);
        }
        return $this->render('index',['calculatedForm' => $calculatedForm  ,'data' => $data]);
    }

    public function actionFeedback(){
        /*return 'Запрос принят!';*/
        $calculatedForm  = new CalculatedForm();
        $data = new Data();
        if($calculatedForm->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){
            $result = $data -> rated[$calculatedForm -> type][$calculatedForm -> tonnage][$calculatedForm -> month];
            $type = $data -> types[$calculatedForm -> type];
            $table = $data -> makeTable($calculatedForm -> type);
            $message =  $data->viewResult($result, $type, $table);
            Yii::$app->response->format = Response::FORMAT_JSON;
            $feedback =[
                'message' => $message
            ];
            return $feedback;
        }
    }
}