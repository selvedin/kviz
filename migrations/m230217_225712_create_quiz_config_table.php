<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%quiz_config}}`.
 */
class m230217_225712_create_quiz_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%quiz_config}}', [
            'id' => $this->primaryKey(),
            'quiz_id' => $this->integer(11)->notNull(),
            'num_of_questions' => $this->integer(11)->notNull(),
            'grade' => $this->integer(11),
            'level' => $this->integer(11),
            'category_id' => $this->integer(11)->notNull(),
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
        $this->dropTable('{{%quiz_config}}');
    }
}
