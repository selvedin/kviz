<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comments}}`.
 */
class m220808_121651_create_comments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comments}}', [
            'id_comment' => $this->primaryKey(),
            'comment' => $this->text()->notNull(),
            'object' => $this->string(512)->notNull(),
            'id_object' => $this->integer(11)->unsigned(),
            'id_parent' => $this->integer(11)->unsigned(),
            'created_on' => $this->string(20),
            'created_by' => $this->integer(11)->unsigned(),
            'updated_on' => $this->string(20),
            'updated_by' => $this->integer(11)->unsigned(),
            'assigned_to' => $this->integer(11)->unsigned(),
            'deleted' => $this->integer(1)->unsigned(),
            'deleted_on' => $this->string(20)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%comments}}');
    }
}
