<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use app\assets\ChatAsset;
use app\assets\FlaggedAsset;
use app\assets\AppAsset;

AppAsset::register($this);
if ($showFlagged) {
    FlaggedAsset::register($this);
} else {
    ChatAsset::register($this);
}
?>
<div class="site-index">
    <div class="jumbotron">
        <h1><?= Html::encode($data['title']) ?></h1>
    </div>
    <div class="body-content">
        <div id="message-container" data-isflagged="<?=$showFlagged?>" data-flag-url="<?=Url::to(['chat/flag'])?>"
            data-confirm="<?=$data['confirm']?>" data-empty="<?=$data['empty']?>">
            <?php
                if (count($messages)):
                     foreach ($messages as $message):
            ?>
                        <div class="message-item row" data-id="<?=$message->id?>">
                            <?php $isBold = User::isAdminById($message->user_id) ? ' bold' : ''; ?>
                            <div class="col-xs-2<?=$isBold?>"><?=Html::encode(User::getUsernameById($message->user_id))?></div>
                            <div class="col-xs-5<?=$isBold?>"><?=Html::encode($message->text)?>)</div>
                            <div class="col-xs-3<?=$isBold?>"><?=Yii::$app->formatter->asDate($message->created_at, 'php:m-d G:i')?></div>
                            <?php
                            if ($data['currentIsAdmin']):
                                if ($showFlagged):
                            ?>
                                    <div class="flag-message col-xs-2"><i class="fas fa-reply"></i></div>
                            <?php
                                else:
                            ?>
                                    <div class="flag-message col-xs-2"><i class="fas fa-flag"></i></div>
                            <?php
                                endif;
                            ?>
                            <?php
                            endif;
                            ?>
                        </div>
            <?php	endforeach;
                else:
            ?>
                    <div class="row" id="no-messages">
                        <div class="col-xs-12"><?=$data['empty']?></div>
                    </div>
            <?php
                endif;
            ?>
        </div>
        <?php
        if (!$showFlagged):
        ?>
            <div class="row" id="add-message-container" data-url="<?=Url::to(['chat/add'])?>" hidden>
                <div id="input-container" data-username="<?=Html::encode(($data['currentUsername']))?>" data-isadmin="<?=$data['currentIsAdmin']?>">
                    <input type="text" id="add-message" class="col-xs-10 col-xs-offset-1" placeholder="Добавить новое сообщение">
                    <i id="send-button" class="fa fa-paper-plane" aria-hidden="true"></i>
                    <i id="loading-svg" class="fas fa-circle-notch fa-spin" hidden></i>
                </div>
            </div>
        <?php
        endif;
        ?>
    </div>
</div>