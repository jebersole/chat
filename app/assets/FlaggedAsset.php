<?php

namespace app\assets;

use yii\web\AssetBundle;

class FlaggedAsset extends AssetBundle
{
    public $css = [
    ];
    public $js = [
        'js/flagged.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset'
    ];
}
