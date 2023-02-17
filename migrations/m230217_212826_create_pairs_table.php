<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pairs}}`.
 */
class m230217_212826_create_pairs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pairs}}', [
            'id' => $this->primaryKey(),
            'question_id' => $this->integer(11)->notNull(),
            'one' => $this->string(1024)->notNull(),
            'two' => $this->string(1024)->notNull(),
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
        $this->dropTable('{{%pairs}}');
    }
}
