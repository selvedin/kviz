<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%quiz}}`.
 */
class m230217_135745_create_quiz_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%quiz}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(1024)->notNull(),
            'num_of_questions' => $this->integer(11)->defaultValue(10),
            'duration' => $this->integer(11)->defaultValue(30),
            'grade' => $this->integer(11),
            'level' => $this->integer(11),
            'school_id' => $this->integer(11),
            'moderator_id' => $this->integer(11),
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
        $this->dropTable('{{%quiz}}');
    }
}
