<?php

use yii\db\Migration;

/**
 * Class m221124_155611_create_table_user
 */
class m221124_155611_create_table_user extends Migration
{
    const TABLE_NAME = 'user';
    const ROLE_ADMIN = 40;
    const ROLE_MODER = 30;
    const ROLE_USER = 20;

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME,[
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull(),
            'password' => $this->string(250)->notNull(),
            'role' => $this->integer(),
            'update_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull(),
            'create_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);

        $this->insert(self::TABLE_NAME,[
            'id' => '1',
            'username' => 'admin',
            'password' => 'admin',
            'role' => 40,
        ]);

        $this->insert(self::TABLE_NAME,[
            'id' => '2',
            'username' => 'demo',
            'password' => 'demo',
            'role' => 20,
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221124_155611_create_table_user cannot be reverted.\n";

        return false;
    }
    */
}
