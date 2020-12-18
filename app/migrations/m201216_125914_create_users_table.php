<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m201216_125914_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
			'username' =>  $this->string()->defaultValue(''),
			'password' =>  $this->string()->defaultValue(''),
			'email' =>  $this->string()->defaultValue(''),
			'created_at' => $this->datetime(),
			'updated_at' => $this->datetime(),
		]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
