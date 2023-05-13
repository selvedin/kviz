<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%api_calls}}`.
 */
class m230317_215215_create_api_calls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%api_calls}}', [
            'id' => $this->primaryKey(),
            'api_key' => $this->string(256)->notNull(),
            'ip' => $this->string(32)->notNull(),
            'total' => $this->integer(11)->unsigned()->notNull(),
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
        $this->dropTable('{{%api_calls}}');
    }
}
