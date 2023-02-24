<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%quiz_results}}`.
 */
class m230217_140532_create_quiz_results_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%quiz_results}}', [
            'id' => $this->primaryKey(),
            'quiz_id' => $this->integer(11)->notNull(),
            'temp_id' => $this->integer(11)->notNull(),
            'competitor_id' => $this->integer(11)->notNull(),
            'results' => $this->string(),
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
        $this->dropTable('{{%quiz_results}}');
    }
}
