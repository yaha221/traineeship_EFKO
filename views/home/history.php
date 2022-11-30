<?php
    $this->title = 'История расчётов';

    use nkostadinov\user\models\User;
    use yii\grid\GridView;
    use yii\data\ActiveDataProvider;
    use yii\widgets\Pjax;
?>
    <div class="row mt-5 p-5">
    <?php Pjax::begin() ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'emptyText' => 'Ничего не найдено',
            'columns' => [
                'id',
                [
                    'class' => \yii\grid\Column::className(),
                    'visible' => \Yii::$app->user->can('admin'),
                    'content' => function ($model){
                        $data = User::findById($model->user_id);
                        return $data['username'];
                    },
                    'header' => 'Пользователь',
                ],
                'month',
                'type',
                'tonnage',
                'created_at',
                [
                    'class' => \yii\grid\ActionColumn::className(),
                    'header' => 'Таблица',
                    'visibleButtons' =>[
                        'delete' => \Yii::$app->user->can('admin'),
                    ],
                    'buttons' => [
                        'view' => function ($url, $model) {
                            $customurl=Yii::$app->getUrlManager()->createUrl(['/home/table','id'=>$model['id']]); 
                            return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $customurl,
                                                    ['title' => Yii::t('yii', 'Посмотреть'), 'data-pjax' => '0']);
                        },
                        'delete' => function ($url, $model) {
                            $customurl=Yii::$app->getUrlManager()->createUrl(['/home/delete','id'=>$model['id']]);
                            return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-trash"></span>', $customurl,
                                                    ['title' => Yii::t('yii', 'Удалить'), 'data-pjax' => '0', 
                                                    'data-confirm' => 'Вы уверены что хотите удалить запрос?', 
                                                    'data-method' => 'post']);
                        }
                    ],
                    'template' => '{view} {delete}',
                ],
            ],
        ]); ?>
    <?php Pjax::end() ?>
    </div>
    <div id="resultTable">

    </div>