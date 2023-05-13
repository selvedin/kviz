<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%question}}`.
 */
class m230513_160226_add_duration_column_to_question_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%question}}', 'duration', $this->double(10,2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%question}}', 'duration');
    }
}
