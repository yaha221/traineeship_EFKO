<?php

use app\assets\ReleaseControlFilterAsset;
use app\components\UHtml;

/**
 * @var $this \yii\web\View
 * @var $service \app\models\release_control\ReleaseControlService
 * @var $searchModel \app\models\release_control\ReleaseControlSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $repository \app\models\release_control\ReleaseControlRepository
 */

ReleaseControlFilterAsset::register($this);

$this->title = 'Применение обновлений';
$this->params['breadcrumbs'][] = ['label' => 'Применение обновлений'];
?>

<div class="dispatchers-motivation-index">

    <div class="text-center h3">Применение обновлений</div>
    <br>
    <br>
    <?= $this->render('_search', [
            'model' => $searchModel,
            'service' => $service,
    ]); ?>

    <?= $this->render('_grid_manage', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'service' => $service,
        'repository' => $repository,
    ]); ?>

</div>