<?php

namespace app\models;

use yii\base\Model;
use Yii;

class CalculatedForm extends Model
{
    public $monthPost;
    public $tonnagePost;
    public $typePost;

    public function setData(){
        $request=Yii::$app->request;
        $this->monthPost = $request->post("month");
        $this->tonnagePost = $request->post("tonnag");
        $this->typePost = $request->post("type");
    }
}