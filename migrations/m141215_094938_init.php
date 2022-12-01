<?php

//namespace nkostadinov\user\migrations;

use app\migrations\Migration;
use yii\db\Schema;

class m141215_094938_init extends Migration
{
    public function up()
    {
        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING,
            'name' => Schema::TYPE_STRING,
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1',
            'created_at' => Schema::TYPE_INTEGER . ' NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NULL',
            'is_alert' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'confirmed_on' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'register_ip' => Schema::TYPE_STRING . '(45) NULL',
            'last_login' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'last_login_ip' => Schema::TYPE_STRING . '(45)',
        ], $this->getTableOptions());
        //Create unique index for email field
        $this->createIndex('unq_email', '{{%user}}', 'email', true);

        $this->insert('user', [
            'username' => 'Админ',
            'name' => 'Админ',
            'auth_key' => 'admin',
            'password_hash' => '$2y$13$hEAhVD2b9u8HQOlReCa8Me2pNTGWtCL5.Br7MznprxF/m9hLQ75ii',
            'email' => 'admin@admin.a',
            'confirmed_on' => 1669732865,
        ]);

        $this->insert('user', [
            'username' => 'Пользователь',
            'name' => 'Пользователь',
            'auth_key' => 'user',
            'password_hash' => '$2y$13$hEAhVD2b9u8HQOlReCa8Me2pNTGWtCL5.Br7MznprxF/m9hLQ75ii',
            'email' => 'user@user.u',
            'confirmed_on' => 1669732865,
        ]);


        //This table holds the linked accounts of the user
        $this->createTable('{{%user_account}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER,
            'provider' => Schema::TYPE_STRING,
            'attributes' => Schema::TYPE_TEXT,
            'access_token' => Schema::TYPE_STRING,
            'expires' => Schema::TYPE_INTEGER,
            'token_create_time' => Schema::TYPE_INTEGER,
            'client_id' => Schema::TYPE_STRING . '(25) NOT NULL',
            'created_at' => $this->integer()->unsigned(),
            'updated_at' => $this->integer()->unsigned(),
        ], $this->getTableOptions());
        //FK to user
        $this->addForeignKey('fk_useraccount_user', '{{%user_account}}', 'user_id', '{{%user}}', 'id');
    }

    public function down()
    {
        $this->dropTable('{{%user_account}}');
        $this->dropTable('{{%user}}');
    }
}
