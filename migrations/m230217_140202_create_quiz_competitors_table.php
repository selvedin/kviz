<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%quiz_competitors}}`.
 */
class m230217_140202_create_quiz_competitors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%quiz_competitors}}', [
            'id' => $this->primaryKey(),
            'quiz_id' => $this->integer(11)->notNull(),
            'temp_id' => $this->integer(11),
            'competitor_id' => $this->integer(11)->notNull(),
            'team_id' => $this->integer(11),
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
        $this->dropTable('{{%quiz_competitors}}');
    }
}
