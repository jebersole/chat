<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%messages}}`.
 */
class m201216_153256_create_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%messages}}', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer()->defaultValue(0),
			'text' => $this->text(),
			'flagged' => $this->boolean()->defaultValue(false),
			'created_at' => $this->datetime(),
        ]);

		$this->addForeignKey(
			'fk-messages-user_id',
			'messages',
			'user_id',
			'users',
			'id',
			'CASCADE'
		);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%messages}}');
    }
}
