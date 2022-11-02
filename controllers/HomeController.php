<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Data;

class HomeController extends Controller
{
    public function actionIndex(){
        $request = Yii::$app->request;
        $data = new Data;
        if ($request->isPost) {
            $monthPost = $request->post("month");
            $tonnagePost = $request->post("tonnag");
            $typePost = $request->post("type");
            return $this->render('index',['monthPost' => $monthPost, 'tonnagePost' => $tonnagePost, 'typePost' => $typePost,'data' => $data]);
        }
        else {
            return $this->render('index',['data' => $data]);
        }
    }
}