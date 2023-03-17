<?php

namespace app\assets;

use app\components\Helper;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class ReleaseControlFilterAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/index.js'
    ];

    /*
     * @var string
     */
    public $sourcePath = '@webroot';

    /*
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];

    /**
     * Кэширование в зависимости от режима
     * работы приложения
     */
    public function init(): void
    {
        parent::init();
        $this->publishOptions['forceCopy'] = (! Helper::isProduction());
    }
}