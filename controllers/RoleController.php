<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\web\Response;
use nkostadinov\user\models\User;
use yii\web\ForbiddenHttpException;
use app\models\AssigmentUser;

/**
 * HomeController за роли пользователей
 */
class RoleController extends Controller
{
    /**
     * Отправляет данные для формы и отрисовки таблицы ролей пользователей
     * 
     * @return mixed
     */
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
        return $this->render('roles', [
            'assigmentsTable' => $assigmentsTable,
            'assigmentsItem' => $assigmentsItem,
            'assigment' => $assigment,
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    /**
     * Добавляет роль пользователю и обновляет страницу
     * 
     * @return mixed
     */
    public function actionAppoint()
    {
        if (!Yii::$app->user->can('admin')){
            throw new ForbiddenHttpException('Access denied');
        }

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

    /**
     * Снимает роль с пользователя и обновляет страницу
     * 
     * @return mixed
     */
    public function actionTakeoff()
    {
        if (!Yii::$app->user->can('admin')){
            throw new ForbiddenHttpException('Access denied');
        }

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