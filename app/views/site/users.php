<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use app\assets\UserAsset;
use app\assets\AppAsset;

AppAsset::register($this);
UserAsset::register($this);
?>
<div class="site-index">
    <div class="jumbotron">
        <h1><?= Html::encode($title) ?></h1>
    </div>
    <div class="body-content">
        <div id="user-container" data-url="<?=Url::to(['users/role'])?>">
            <?php
                if (count($users)):
                     foreach ($users as $user):
            ?>
                        <div class="row user-item">
                            <div class="col-xs-4"><?=Html::encode($user->username)?></div>
                            <div class="col-xs-4"><?=Html::encode($user->email)?></div>
                            <div class="col-xs-4">
                                <select class="user-role-selector" data-id="<?=$user->id?>">
                                    <?php
                                        $userRole = $user->getRole();
                                        foreach (User::ROLES as $role): ?>
                                            <option value="<?=$role?>"
                                                <?=$userRole === $role ? 'selected' : '' ?>><?=ucfirst($role)?></option>
                                    <?php
                                        endforeach; ?>
                                </select>
                            </div>
                        </div>
            <?php	endforeach;
                else:
                    ?>
                    <div class="row" id="no-messages">
                        <div class="col-xs-12"><?=$empty?></div>
                    </div>
                <?php
                endif;
                ?>
        </div>
    </div>
</div>