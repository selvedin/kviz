<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%roles}}`.
 */
class m220820_200226_create_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%roles}}', [
            'id_role' => $this->primaryKey(),
            'name' => $this->string(256)->notNull(),
            'private' => $this->integer(1)->unsigned(),
            'description' => $this->string(1000),
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
        $this->dropTable('{{%roles}}');
    }
}
