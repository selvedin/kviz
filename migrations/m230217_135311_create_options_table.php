<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%options}}`.
 */
class m230217_135311_create_options_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%options}}', [
            'id' => $this->primaryKey(),
            'question_id' => $this->integer(11)->notNull(),
            'content' => $this->string(1024)->notNull(),
            'is_true' => $this->integer(1)->defaultValue(0),
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
        $this->dropTable('{{%options}}');
    }
}
