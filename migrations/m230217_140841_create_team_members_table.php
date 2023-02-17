<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%team_members}}`.
 */
class m230217_140841_create_team_members_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%team_members}}', [
            'id' => $this->primaryKey(),
            'team_id' => $this->integer(11)->notNull(),
            'member_id' => $this->integer(11)->notNull(),
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
        $this->dropTable('{{%team_members}}');
    }
}
