<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\models\User;

class Message extends \yii\db\ActiveRecord
{
    static $chat = [
        'title' => 'Чат',
        'empty' => 'Еще никто не отправил сообщение.',
        'confirm' => 'Вы уверены, что хотите пометить это сообщение?',
    ];
    static $flagged = [
        'title' => 'Помеченные сообщения',
        'empty' => 'Нет помеченных сообщений.',
        'confirm' => 'Вы уверены, что хотите вернуть это сообщение в чат?',
    ];
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['text', 'user_id'], 'required'],
            ['user_id', 'integer', 'min' => 1]
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    public static function tableName()
    {
        return '{{messages}}';
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function getMessages($flagged = false)
    {
        return self::find()
            ->where(['flagged' => $flagged])
            ->with('user')
            ->orderBy('created_at ASC')
            ->all();
    }

}