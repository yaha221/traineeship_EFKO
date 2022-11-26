<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
/**
 * Инициализатор RBAC выполняется в консоли php yii my-rbac/init
 */
class MyRbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;

        $auth->removeAll(); 

        $admin = $auth->createRole('admin');
        $moderator = $auth->createRole('moderator');
        $user = $auth->createRole('user');
        
        // запишем их в БД
        $auth->add($admin);
        $auth->add($moderator);
        $auth->add($user);

        $viewAdminPage = $auth->createPermission('viewAdminPage');
        $viewAdminPage->description = 'Просмотр админ панели';
        
        $logoutUser = $auth->createPermission('logoutUser');
        $logoutUser->description = 'Выход из учётной записи';

        // Запишем эти разрешения в БД
        $auth->add($viewAdminPage);
        $auth->add($logoutUser);

        $auth->addChild($user,$logoutUser);

        $auth->addChild($admin, $user);

        $auth->addChild($admin, $viewAdminPage);

        $auth->assign($admin, 6);

        $auth->assign($user, 5);
    }
}