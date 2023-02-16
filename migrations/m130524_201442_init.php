<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'first_name' => $this->string(128)->defaultValue(null),
            'last_name' => $this->string(128)->defaultValue(null),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'verification_token' => $this->string()->defaultValue(null),
            'status' => $this->smallInteger()->notNull()->defaultValue(9),
            'role_id' => $this->string(128)->defaultValue("user"),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(11),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(11),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
