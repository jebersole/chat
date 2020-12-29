<?php

namespace app\assets;

use yii\web\AssetBundle;

class UserAsset extends AssetBundle
{
    public $css = [
    ];
    public $js = [
        'js/user.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset'
    ];
}
