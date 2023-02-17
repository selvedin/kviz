<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%perms}}`.
 */
class m220820_185544_create_perms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%perms}}', [
            'id' => $this->primaryKey(),
            'object' => $this->string(256)->notNull(),
            'perms' => $this->text(),
            'created_at' => $this->integer(11)->unsigned(),
            'created_by' => $this->integer(11)->unsigned(),
            'updated_at' => $this->integer(11)->unsigned(),
            'updated_by' => $this->integer(11)->unsigned(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%perms}}');
    }
}
