<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\models\User;

class Message extends \yii\db\ActiveRecord
{
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
}