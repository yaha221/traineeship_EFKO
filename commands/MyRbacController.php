<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
/**
 * Инициализатор RBAC
 */
class MyRbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;

        $auth->removeAll(); 

        $admin = $auth->createRole('admin');
        $user = $auth->createRole('user');
        
        $auth->add($admin);
        $auth->add($user);

        $viewAdminPage = $auth->createPermission('viewAdminPage');
        $viewAdminPage->description = 'Просмотр админ панели';
        
        $logoutUser = $auth->createPermission('logoutUser');
        $logoutUser->description = 'Выход из учётной записи';

        $auth->add($viewAdminPage);
        $auth->add($logoutUser);

        $auth->addChild($user,$logoutUser);

        $auth->addChild($admin, $user);

        $auth->addChild($admin, $viewAdminPage);

        $auth->assign($admin, 1);

        $auth->assign($user, 2);
    }
}