<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%files}}`.
 */
class m220820_182352_create_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%files}}', [
            'id_file' => $this->primaryKey(),
            'title' => $this->string(1500),
            'name' => $this->string(1600),
            'f_name' => $this->string(5000),
            'f_type' => $this->integer(11)->unsigned(),
            'f_ext' => $this->string(128),
            'f_size' => $this->string(128),
            'f_date' => $this->string(128),
            'object' => $this->string(512),
            'id_object' => $this->integer(11)->unsigned(),
            'tags' => $this->text(),
            'deleted' => $this->integer(1)->unsigned(),
            'private' => $this->integer(1)->unsigned(),
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
        $this->dropTable('{{%files}}');
    }
}
