<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%team}}`.
 */
class m230217_140736_create_team_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%team}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(1024)->notNull(),
            'school' => $this->integer(11),
            'team_lead' => $this->integer(11),
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
        $this->dropTable('{{%team}}');
    }
}
