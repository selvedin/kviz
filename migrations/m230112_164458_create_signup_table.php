<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%signup}}`.
 */
class m230112_164458_create_signup_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%signup}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string(255)->notNull(),
            'token' => $this->string(255)->notNull(),
            'expiry_date' => $this->integer()->unsigned(),
            'is_active' => $this->integer(1)->unsigned(),
            'created_at' => $this->integer(11),
            'created_by' => $this->integer(11)->unsigned(),
            'updated_at' => $this->integer(11),
            'updated_by' => $this->integer(11)->unsigned(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%signup}}');
    }
}
