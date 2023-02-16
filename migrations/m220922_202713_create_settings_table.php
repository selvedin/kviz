<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%settings}}`.
 */
class m220922_202713_create_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(512)->notNull(),
            'title' => $this->string(512),
            'type' => $this->string(512),
            'parent' => $this->string(512),
            'str_value' => $this->string(512),
            'text_value' => $this->text(),
            'int_value' => $this->integer(),
            'decimal_value' => $this->decimal(10, 2),
            'created_on' => $this->string(20),
            'created_by' => $this->integer(11)->unsigned(),
            'updated_on' => $this->string(20),
            'updated_by' => $this->integer(11)->unsigned(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%settings}}');
    }
}
