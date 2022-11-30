<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\web\Response;
use nkostadinov\user\models\User;
use yii\web\ForbiddenHttpException;
use app\models\AssigmentUser;

class RoleController extends Controller
{
    public function actionIndex()
    {
        if (!Yii::$app->user->can('admin')){
            throw new ForbiddenHttpException('Access denied');
        }
        $roles = [];
        foreach(Yii::$app->authManager->roles as $key => $value) {
            $roles[] = $key;
        }
        $users = [];
        foreach(User::find()->all() as $user) {
           $users[$user['id']] =  $user['username'];
        }
        foreach($users as $key => $username) {
            if (Yii::$app->user->identity->username === $username) {
                unset($users[$key]);
            };
        }
        $assigment = new AssigmentUser();
        if (Yii::$app->request->isAjax && $assigment->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($assigment);
        }
        foreach ($assigment->getUserAssigs() as $value) {
            $assigmentsUser[] = $value['user_id'];
            if (Yii::$app->user->id === intval($value['user_id'])) {
                continue;
            }
            $assigmentsItem[] = $value['item_name'];
        }
        foreach($users as $userKey => $username) {
            foreach ($assigmentsUser as $value) {
                if (intval($value) === $userKey) {
                    $assigmentsTable[] = $username;
                }
            }
        }
        /*echo '<pre>';
        var_dump($assigmentsTable);
        var_dump($assigmentsItem);
        echo '<pre>';
        die;*/
        return $this->render('roles', [
            'assigmentsTable' => $assigmentsTable,
            'assigmentsItem' => $assigmentsItem,
            'assigment' => $assigment,
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function actionAppoint()
    {
        $assigment = new AssigmentUser();

        if ($assigment->load(Yii::$app->request->post()) === false || $assigment->validate() === false) {
            return $this->redirect('/');
        }
        $roles = [];
        foreach(Yii::$app->authManager->roles as $key => $value) {
            $roles[] = $key;
        }

        $user_id = $assigment->user_id;
        $item_name = $roles[$assigment->item_name];
        $assigment->appointUserAssig($item_name, $user_id);

        return $this->redirect('/role');
    }
    
    public function actionTakeoff()
    {
        $assigment = new AssigmentUser();

        if ($assigment->load(Yii::$app->request->post()) === false || $assigment->validate() === false) {
            return $this->redirect('/');
        }
        $roles = [];
        foreach(Yii::$app->authManager->roles as $key => $value) {
            $roles[] = $key;
        }

        $user_id = $assigment->user_id;
        $item_name = $roles[$assigment->item_name];
        $assigment->takeoffUserAssig($item_name, $user_id);

        return $this->redirect('/role');
    }
}