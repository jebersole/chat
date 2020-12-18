<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/chat.css',
        'css/site.css',
        'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css',
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset'
    ];
}
