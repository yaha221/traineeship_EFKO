<?php

namespace app\controllers;

use app\models\CalculatedForm;
use Yii;
use yii\web\Controller;
use app\models\Data;

class HomeController extends Controller
{
    public function actionIndex(){
        $calculatedForm  = new CalculatedForm();
        $data = new Data();
        if($calculatedForm->load(Yii::$app->request->post()) && $calculatedForm->validate()){
            return $this->render('indexPost',['calculatedForm' => $calculatedForm  ,'data' => $data]);
        }
        return $this->render('index',['calculatedForm' => $calculatedForm  ,'data' => $data]);
    }

    public function actionTest(){
        return 'test';
    }
}
/*
;
$tonnages = $data->tonnages;
$types = $data->types;
$rated= $data->rated;
$monthPost = $form->monthPost;
$tonnagePost = $form->tonnagePost;
$typePost = $form->typePost;
*/