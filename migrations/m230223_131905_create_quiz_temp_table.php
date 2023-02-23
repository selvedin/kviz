<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%quiz_temp}}`.
 */
class m230223_131905_create_quiz_temp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%quiz_temp}}', [
            'id' => $this->primaryKey(),
            'quiz_id' => $this->integer(11)->unsigned()->notNull(),
            'quiz' => $this->text(),
            'results' => $this->text(),
            'active' => $this->smallInteger(2),
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
        $this->dropTable('{{%quiz_temp}}');
    }
}
