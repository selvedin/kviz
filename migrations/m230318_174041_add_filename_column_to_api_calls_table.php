<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%app_calls}}`.
 */
class m230318_174041_add_filename_column_to_api_calls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%api_calls}}', 'filename', $this->string(256));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%api_calls}}', 'filename');
    }
}
