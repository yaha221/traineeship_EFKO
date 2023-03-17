<?php

namespace app\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use yii\web\NotFoundHttpException;
use nkostadinov\user\controllers\AdminController as BaseAdminController;
use app\models\repositories\DataRepository;
use app\models\admin\Search;
use app\models\user\User;
use app\models\admin\Assigment;


class AdminController extends BaseAdminController
{
    /**
     * Список всех пользователей.
     * 
     * @return mixed
     */
    public function actionUsers()
    {
        if (Yii::$app->user->can('viewAdminPage') === false) {
            throw new ForbiddenHttpException('Доступ запрещён');
        }

        $searchModel = new Search([]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $repository = new DataRepository();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'repository' => $repository,
        ]);
    }

    /**
     * Делает пользователя неактивным.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('viewAdminPage') === false) {
            throw new ForbiddenHttpException('Доступ запрещён');
        }

        call_user_func([Yii::$app->user->identityClass, 'deleteById'], ['id' => $id]);

        return $this->redirect(['users']);
    }

    /**
     * Отправляет данные для формы и отрисовки таблицы ролей пользователей
     * 
     * @return mixed
     */
    public function actionRole()
    {
        if (Yii::$app->user->can('admin') === false){
            throw new ForbiddenHttpException('Доступ запрещён');
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
            }
        }

        $assigment = new Assigment();
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
        if (Yii::$app->user->can('admin') === false){
            throw new ForbiddenHttpException('Доступ запрещён');
        }

        $assigment = new Assigment();

        if ($assigment->load(Yii::$app->request->post()) === false || $assigment->validate() === false) {
            throw new NotFoundHttpException('Страница не найдена!');
        }

        $roles = [];

        foreach(Yii::$app->authManager->roles as $key => $value) {
            $roles[] = $key;
        }

        $userId = $assigment->user_id;
        $itemName = $roles[$assigment->item_name];
        $assigment->appointUserAssig($itemName, $userId);

        return $this->redirect('/admin/role');
    }

    /**
     * Снимает роль с пользователя и обновляет страницу
     * 
     * @return mixed
     */
    public function actionTakeoff()
    {
        if (!Yii::$app->user->can('admin')){
            throw new ForbiddenHttpException('Доступ запрещён');
        }

        $assigment = new Assigment();

        if ($assigment->load(Yii::$app->request->post()) === false || $assigment->validate() === false) {
            throw new NotFoundHttpException('Страница не найдена!');
        }

        $roles = [];
        
        foreach(Yii::$app->authManager->roles as $key => $value) {
            $roles[] = $key;
        }

        $userId = $assigment->user_id;
        $itemName = $roles[$assigment->item_name];
        $assigment->takeoffUserAssig($itemName, $userId);

        return $this->redirect('/admin/role');
    }

    /**
     * Ajax-поиск по именам пользователей
     * 
     * @param string $q
     * @return array
     */
    public function actionUsernamesearch($q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $usernames = ['results' => ['id' => '', 'text' => '']];
        $repository = new DataRepository();

        if (empty($q) === false) {
            $usernames['results'] = $repository->findUsersByUsernameForUsers($q);
        }

        return $usernames;
    }

    /**
     * Ajax-поиск по ip входа пользователей
     * 
     * @param string $q
     * @return array
     */
    public function actionLastloginipsearch($q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $lastLoginIp = ['results' => ['id' => '', 'text' => '']];
        $repository = new DataRepository();

        if (empty($q) === false) {
            $lastLoginIp['results'] = $repository->findUsersByLastLoginIp($q);
        }

        return $lastLoginIp;
    }

    /**
     * Ajax-поиск по ip регистрации пользователей
     * 
     * @param string $q
     * @return array
     */
    public function actionRegisteripsearch($q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $registerIp = ['results' => ['id' => '', 'text' => '']];
        $repository = new DataRepository();

        if (empty($q) === false) {
            $registerIp['results'] = $repository->findUsersByRegisterIp($q);
        }

        return $registerIp;
    }

    /**
     * Ajax-поиск по электронной почте пользователей
     * 
     * @param string $q
     * @return array
     */
    public function actionEmailsearch($q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $email = ['results' => ['id' => '', 'text' => '']];
        $repository = new DataRepository();

        if (empty($q) === false) {
            $email['results'] = $repository->findUsersByEmail($q);
        }

        return $email;
    }
}