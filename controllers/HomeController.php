<?php

namespace app\controllers;

use app\models\CalculatedForm;
use Yii;
use yii\web\Controller;
use app\models\Data;

class HomeController extends Controller
{
    public function actionIndex(){
        $form = new CalculatedForm;
        $data = new Data;
        $form->load(Yii::$app->request->post());
        $form->setData();
        return $this->render('index',['form' => $form,'data' => $data]);
    }

    public function actionTest(){
        return 'test';
    }
}