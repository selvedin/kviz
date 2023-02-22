<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_subject}}`.
 */
class m230222_125855_create_user_subject_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_subject}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull()->unsigned(),
            'subject_id' => $this->integer(11)->notNull()->unsigned(),
            'perms' => $this->integer(11)->unsigned(),
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
        $this->dropTable('{{%user_subject}}');
    }
}
