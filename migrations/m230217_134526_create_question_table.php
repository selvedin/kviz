<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%question}}`.
 */
class m230217_134526_create_question_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%question}}', [
            'id' => $this->primaryKey(),
            'content' => $this->string(1024)->notNull(),
            'question_type' => $this->integer(11)->defaultValue(1),
            'content_type' => $this->integer(11)->defaultValue(1),
            'category_id' => $this->integer(11),
            'status' => $this->integer(11),
            'grade' => $this->integer(11),
            'level' => $this->integer(11),
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
        $this->dropTable('{{%question}}');
    }
}
