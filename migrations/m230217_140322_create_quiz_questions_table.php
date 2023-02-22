<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%quiz_questions}}`.
 */
class m230217_140322_create_quiz_questions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%quiz_questions}}', [
            'id' => $this->primaryKey(),
            'quiz_id' => $this->integer(11)->notNull(),
            'question_id' => $this->integer(11)->notNull(),
            'duration' => $this->double(5, 2),
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
        $this->dropTable('{{%quiz_questions}}');
    }
}
