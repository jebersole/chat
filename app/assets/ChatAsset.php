<?php

namespace app\assets;

use yii\web\AssetBundle;

class ChatAsset extends AssetBundle
{
    public $css = [
    ];
    public $js = [
        'js/chat.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset'
    ];
}
